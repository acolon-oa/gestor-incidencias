<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        $audit_logs = \App\Models\AuditLog::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.audit-logs.index', compact('audit_logs'));
    }

    public function show($id)
    {
    }
}
