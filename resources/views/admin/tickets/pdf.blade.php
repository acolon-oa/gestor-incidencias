<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket #{{ $ticket->id }}</title>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.6; padding: 20px; }
        h1 { font-size: 22px; margin-bottom: 5px; color:#111; }
        .meta { font-size: 12px; color: #666; margin-bottom: 30px; border-bottom: 1px solid #ddd; padding-bottom: 15px; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: uppercase; margin-right: 5px; color: #fff;}
        .badge.open { background: #ef4444; }
        .badge.in_progress { background: #f59e0b; }
        .badge.closed { background: #10b981; }
        .badge-priority { background: #6b7280; }
        .section-title { font-size: 14px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #888; border-bottom: 1px solid #eee; padding-bottom: 5px; margin-top: 30px; margin-bottom: 15px; }
        .description { background: #f9f9f9; padding: 15px; border-radius: 6px; font-size: 14px; white-space: pre-line; }
        .comment { border: 1px solid #eee; margin-bottom: 10px; padding: 10px 15px; border-radius: 6px; }
        .comment-header { font-size: 12px; color: #666; margin-bottom: 8px; font-weight: bold; border-bottom: 1px dashed #eee; padding-bottom: 5px; }
        .comment-body { font-size: 13px; }
    </style>
</head>
<body>
    <h1>Ticket #{{ $ticket->id }} - {{ $ticket->title }}</h1>
    <div class="meta">
        <span class="badge {{ $ticket->status }}">{{ str_replace('_', ' ', $ticket->status) }}</span>
        <span class="badge badge-priority">{{ $ticket->priority }} PRIORITY</span>
        <br><br>
        <strong>Reported by:</strong> {{ $ticket->user->name }} &nbsp;&nbsp;|&nbsp;&nbsp; 
        <strong>Department:</strong> {{ $ticket->department?->name ?? 'None' }} &nbsp;&nbsp;|&nbsp;&nbsp;
        <strong>Created at:</strong> {{ $ticket->created_at->format('Y-m-d H:i') }}
    </div>

    <div class="section-title">Description</div>
    <div class="description">{{ $ticket->description }}</div>

    <div class="section-title">Activity & Comments</div>
    @if($ticket->comments->isEmpty())
        <p style="font-size: 14px; color: #888; font-style: italic;">No comments inside this ticket.</p>
    @else
        @foreach($ticket->comments as $comment)
            <div class="comment">
                <div class="comment-header">
                    {{ $comment->user->name }} - {{ $comment->created_at->format('Y-m-d H:i') }}
                </div>
                <div class="comment-body">
                    {{ $comment->content }}
                </div>
            </div>
        @endforeach
    @endif
</body>
</html>
