<?php
namespace App\Modules\Basicpage\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Modules\Basicpage\Models\Basicpage;

class BasicPageApiController extends Controller
{

    /**
     * Get guard
     */
    protected function getGuard()
    {
        return 'api';
    }

    /**
     * Create A New Item Controller Instance.
     *
     * @return Void
     */
    public function __construct()
    {
        $this->middleware([
            'auth:api'
        ], [
            'except' => [
                'load'
            ]
        ]);
    }

    /**
     * Load basic page
     *
     * @param Request $request
     * @param string $slug
     *
     * @return Response json encoded data
     */
    public function load(Request $request, $slug)
    {
        $result = [
            '#status' => 'error',
            '#message' => 'Oops! Something goes wrong in ' . __FUNCTION__,
            '#data' => []
        ];

        $basicPage = Basicpage::where('slug', $slug)->first();
        if (empty($basicPage)) {
            $result['#message'] = "Page {$slug} is not available";
        } else {
            $result['#status'] = 'success';
            $result['#message'] = 'Data update available';

            $result['#data']['body'] = $basicPage->body;
            $result['#data']['title'] = $basicPage->title;
        }

        return response()->json($result);
    }
}
