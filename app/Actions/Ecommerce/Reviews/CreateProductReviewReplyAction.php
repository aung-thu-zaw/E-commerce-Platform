<?php

namespace App\Actions\Ecommerce\Reviews;

use App\Models\Reply;

class CreateProductReviewReplyAction
{
    /**
     * @param  array<mixed>  $data
     */
    public function handle(array $data): Reply
    {
        $reply = Reply::create([
            'product_review_id' => $data['product_review_id'],
            'seller_id' => $data['seller_id'],
            'reply_text' => $data['reply_text'],
        ]);

        return $reply;
    }
}
