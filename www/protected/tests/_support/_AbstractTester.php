<?php
use Codeception\Scenario;

/**
 *
 * @property \Fixture\Database $db
 */
abstract class _AbstractTester extends \Codeception\Actor
{

    /**
     * construction
     *
     * @param Scenario $scenario
     */
    public function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);

        $this->db = new \Fixture\Database();
    }

    /**
     * Gerenate hash
     *
     * @return string
     */
    public function generateHash()
    {
        return substr(md5(microtime() . mt_rand()), 0, 8);
    }

    /**
     * Format btc
     *
     * @param double $btc
     * @return string
     */
    public function btcFormat($btc)
    {
        $number = number_format(doubleval($btc), 8);

        $result = rtrim($number, "0");
        $result = rtrim($result, ".");

        return $result;
    }

    public function getSandboxBaseUrl()
    {
        $baseUrl = env('APP_URL');

        // create dynamic url for qa test
        if ($baseUrl == 'http://sandbox') {
            $rootPath = $this->getAppRootPath();
            $idx = strrpos($rootPath, DIRECTORY_SEPARATOR);
            $parentFolder = substr($rootPath, $idx);
            $baseUrl = $baseUrl . $parentFolder;
        }

        return $baseUrl;
    }

    public function prepareProjectReadyToPublish($data = [])
    {
        $hash = $this->generateHash();
        $user = $this->db->user->create();
        $managedFile = $this->db->managedFile->create([
            'sourceFile' => 'photo.jpg'
        ]);
        $projectModel = $this->db->project->create($data + [
            'name' => "_project_name_{$hash}",
            'project_category_id' => 1,
            'logo_fid' => $managedFile->id,
            'description' => "_description_{$hash}",
            'project_type_id' => \App\Modules\Taxonomy\Models\ProjectType::TYPE_TOKEN,
            'project_platform_id' => 5,
            'token_symbol' => 'BTC',
            'total_supply' => 12345678.9,
            'whitepaper_url' => "http://whitepaper.example.com",
            'has_referral' => 1,
            'location' => "_location_{$hash}",
            'status' => \App\Modules\Project\Models\Project::STATUS_CONFIRMED,
            'oid' => $user->oid,
        ]);

        // add links
        $linkType = \App\Modules\Taxonomy\Models\LinkType::find(\App\Modules\Taxonomy\Models\LinkType::TYPE_WEBSITE);
        $projectModel->links()->attach($linkType, [
            'url' => "http://website.example.com"
        ]);

        $linkType = \App\Modules\Taxonomy\Models\LinkType::find(\App\Modules\Taxonomy\Models\LinkType::TYPE_BITCOIN_TALK);
        $projectModel->links()->attach($linkType, [
            'url' => "http://bitcointalk.example.com"
        ]);

        $linkType = \App\Modules\Taxonomy\Models\LinkType::find(\App\Modules\Taxonomy\Models\LinkType::TYPE_BOUNTY_INFO);
        $projectModel->links()->attach($linkType, [
            'url' => "http://bountyinfo.example.com"
        ]);

        // add member
        $member = $this->db->member->create([
            'oid' => $projectModel->oid
        ]);
        $projectModel->members()->attach($member, [
            'position' => "_position_$hash"
        ]);

        // add event
        $eventModel = $this->db->events->create([
            'project_id' => $projectModel->id
        ]);

        // create order
        $orderModel = $this->db->order->create([
            'project_id' => $projectModel->id
        ]);

        // create order item for listing item
        $orderItemListingItem = $this->db->orderItem->create([
            'order_id' => $orderModel->id,
            'item_type' => \App\Modules\Order\Models\OrderItem::TYPE_LISTING_ITEM
        ]);

        // create order item for a marketing extra
        $orderItemMarketingExtra = $this->db->orderItem->create([
            'order_id' => $orderModel->id,
            'item_type' => \App\Modules\Order\Models\OrderItem::TYPE_MARKETING_EXTRA
        ]);

        return $projectModel;
    }

    /**
     * Execute artisan command line
     *
     * @param string $command,
     *            without artisan part
     * @return array
     */
    public function executeArtisanCommand($command)
    {
        $result = [
            'command' => $command,
            'outputs' => [],
            'return' => NULL
        ];

        $rootPath = $this->getAppRootPath();
        $artisanCommand = "php {$rootPath}/protected/artisan " . $command;
        $result['command'] = escapeshellcmd($artisanCommand);

        exec($result['command'], $result['outputs'], $result['return']);

        $result['output'] = implode("\n", $result['outputs']);

        return $result;
    }

    public function getAppRootPath()
    {
        $filePath = dirname(__FILE__);
        $rootPath = substr($filePath, 0, strlen($filePath) - strlen("/protected/tests/_support"));

        return $rootPath;
    }
}