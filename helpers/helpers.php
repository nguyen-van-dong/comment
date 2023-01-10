<?php

use Module\Comment\Models\Comment;

if (!function_exists('truncate'))
{
    /**
     * @param $str
     * @param $width
     * @return string
     */
    function truncate($str, $width = 20) {
        return strtok(wordwrap($str, $width, "...\n"), "\n");
    }
}

if (!function_exists('get_comment'))
{
    function get_comment($id)
    {
        $comment = $comment = Comment::withDepth()->find($id);
        $data = $comment->toArray();
        $data['depth'] = $comment->depth;
        $data['customer_name'] = $comment->customer->name;
        $data['diffForHumans'] = $comment->created_at->diffForHumans();
        if ($comment->getPrevSibling() && $comment->parent) {
            $data['sibling_node_id'] = $comment->getPrevSibling()->id;
        }
        if ($comment->parent) {
            $data['parent_node_id'] = $comment->parent->id;
        }
        return $data;
    }
}

if (!function_exists('total_comments'))
{
    /**
     * @param $pageId
     * @return int
     */
    function total_comments($pageId)
    {
        return Comment::wherePageId($pageId)->whereIsPublished(1)->whereNull('parent_id')->count();
    }
}
