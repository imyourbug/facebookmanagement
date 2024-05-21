<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function getLinkOrPostIdFromUrl(string $url = '')
    {
        $url = explode('/', $url ?? '');

        return  count($url) ? $url[count($url) - 1] : '';
    }

    public function syncPointToLinkBeforeUpdateLink(string $link_id, string $parent_link_or_post_id)
    {
        if ($parent_link_or_post_id) {
            // prent links
            $parent_child = Link::firstWhere('link_or_post_id', $parent_link_or_post_id);
            // get link
            $link = Link::with([
                'userLinksWithTrashed.user', 'userLinks.user',
                'commentLinks.comment', 'reactionLinks.reaction'
            ])->firstWhere('id', $link_id);
            $userLinks =  $link->userLinks ?? [];
            // $commentLinks =  $link->commentLinks ?? [];
            // $reactionLinks =  $link->reactionLinks ?? [];

            // update to point to parent link
            foreach ($userLinks as $userLink) {
                $userLink->update([
                    'link_id' => $parent_child->id,
                ]);
            }

            // foreach ($commentLinks as $commentLink) {
            //     $commentLink->update([
            //         'link_id' => $parent_child->id,
            //     ]);
            // }

            // foreach ($reactionLinks as $reactionLink) {
            //     $reactionLink->update([
            //         'link_id' => $parent_child->id,
            //     ]);
            // }
        }
    }

    public function syncPointToLinkBeforeCreateLink(array $data)
    {
        // get link

        // current
        return Link::firstOrCreate(
            ['link_or_post_id' => $data['link_or_post_id']],
            [
                'title' =>  $data['title'] ?? '',
                'is_scan' => $data['is_scan'] ?? '',
                'type' => $data['type'] ?? '',
                'delay' => $data['delay'] ?? '',
                'status' => $data['status'] ?? '',
            ]
        );

        // old
        // $link = Link::where('link_or_post_id', $data['link_or_post_id'] ?? '')
        //     ->first();

        // return $link && $link->parentLink ? $link->parentLink : Link::firstOrCreate(
        //     ['link_or_post_id' => $data['link_or_post_id']],
        //     [
        //         'title' =>  $data['title'] ?? '',
        //         'is_scan' => $data['is_scan'] ?? '',
        //         'type' => $data['type'] ?? '',
        //         'delay' => $data['delay'] ?? '',
        //         'status' => $data['status'] ?? '',
        //     ]
        // );
    }
}
