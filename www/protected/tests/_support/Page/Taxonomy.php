<?php
namespace Page;

class Taxonomy
{

    protected $tester;

    public function __construct(\Codeception\Actor $tester)
    {
        $this->tester = $tester;
    }

    public function createTerm($term, $data = [])
    {
        $meta = $this->getMeta($term);
        $fixture = $meta['fixture'];
        return $this->tester->db->{$fixture}->create($data);
    }

    public function goToPage($term)
    {
        $meta = $this->getMeta($term);
        $this->tester->amOnPage("taxonomy/{$term}");
        $this->tester->waitForText($meta['listTitle'], $this->tester->defaultWait, '.nav-tabs li.active');
        $this->tester->dataTable->waitForProcessingComplete();
    }

    public function followCreateLink($term)
    {
        $meta = $this->getMeta($term);
        $createButton = "a[href$=\"/taxonomy/view/{$term}/create\"]";
        $this->tester->waitForElement($createButton);
        $this->tester->click($createButton);
        $this->tester->seeSectionHeaderText('Create ' . $meta['modelName']);
    }

    public function followEditLink($term, $model)
    {
        $meta = $this->getMeta($term);
        $editButton = "a[href$=\"/taxonomy/view/{$term}/{$model->id}\"]";
        $this->tester->waitForElement($editButton);
        $this->tester->click($editButton);
        $this->tester->seeSectionHeaderText('Edit ' . $meta['modelName']);
    }

    public function submitForm($term, $data = [])
    {
        $meta = $this->getMeta($term);
        $form = "form[action$=\"/taxonomy/process/{$term}\"]";

        $data = $data + [
            'name' => "_test_{$term}_" . $this->tester->generateHash()
        ];

        $this->tester->submitForm($form, $data, 'button[type="submit"]');
    }

    private function getMeta($term)
    {
        $meta = $this->taxonomyMetas[$term];
        if (empty($meta)) {
            abort(404, t('Meta of :term not found.', [
                ':term' => $term
            ]));
        }

        return $meta;
    }

    private $taxonomyMetas = [
        'variable' => [
            'fixture' => 'variable',
            'dbTable' => 'variables',
            'listTitle' => 'Variables',
            'modelName' => 'Variable'
        ]
    ];
}
