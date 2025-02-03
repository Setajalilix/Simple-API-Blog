<?php

namespace App\Http\Controllers\Design;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Repositories\Contracts\IComment;
use App\Repositories\Contracts\IDesign;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    protected $comments;
    protected $design;

    public function __construct(IComment $comments, IDesign $design)
    {
        $this->comments = $comments;
        $this->design = $design;
    }

    public function store($designId, Request $request)
    {
        $request->validate([
            'body' => 'required',
        ]);
        $comment = $this->design->addComment($designId, [
            'user_id' => Auth::id(),
            'body' => $request->body,
        ]);

        return new CommentResource($comment);
    }

    public function update($id, Request $request)
    {
        $comment = $this->comments->find($id);
        $this->authorize('update', $comment);

        $request->validate([
            'body' => 'required',
        ]);
        $comment->update([
            'body'=>$request->body
        ]);
        return new CommentResource($comment);
    }

    public function destroy($id)
    {
        $comment = $this->comments->find($id);
        $this->authorize('delete', $comment);

        $comment->delete($id);

        return response()->json(['message' => 'comment deleted'],200);
    }
}
