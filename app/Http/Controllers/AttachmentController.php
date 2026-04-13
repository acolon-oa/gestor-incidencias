<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class AttachmentController extends Controller
{
    public function download(Attachment $attachment)
    {
        // Check if file exists
        if (!Storage::disk('public')->exists($attachment->path)) {
            abort(404, 'File not found');
        }

        // Return download with original filename
        return Storage::disk('public')->download($attachment->path, $attachment->filename);
    }
}
