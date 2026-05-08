@extends('layouts.app')

@section('title', 'Tasks')
@section('page_title', 'Tasks')
@section('page_subtitle', 'Tasks linked to leads, contacts, deals, and support tickets.')
@section('actions')
    <div class="actions">
        <a class="btn-secondary" href="{{ route('tasks.index', ['format' => 'csv']) }}">Export CSV</a>
        <a class="btn" href="{{ route('tasks.create') }}">New Task</a>
    </div>
@endsection

@section('content')
    <section class="panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Related</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Due</th>
                        <th>Assignee</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                        <tr>
                            <td>
                                <strong>{{ $task->title }}</strong>
                                <div class="muted">{{ $task->description }}</div>
                            </td>
                            <td>{{ class_basename($task->related_type ?? '') ?: 'Unlinked' }} @if($task->related) · #{{ $task->related->id }} @endif</td>
                            <td><span class="badge blue">{{ \Illuminate\Support\Str::headline($task->status) }}</span></td>
                            <td>{{ ucfirst($task->priority) }}</td>
                            <td>{{ $task->due_date?->format('M j, Y') ?? 'No due date' }}</td>
                            <td>{{ $task->assignedUser?->name ?? 'Unassigned' }}</td>
                            <td><a class="btn-link" href="{{ route('tasks.edit', $task) }}">Edit</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="muted">No tasks found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    {{ $tasks->links() }}
@endsection
