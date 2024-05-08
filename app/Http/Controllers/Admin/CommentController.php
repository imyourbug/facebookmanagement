<?php

namespace App\Http\Controllers\Admin;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.comment.list', [
            'title' => 'Danh sách bình luận',
        ]);
    }
}
