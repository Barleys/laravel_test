@extends('layouts.app')

@section('content')

        <!-- Bootstrap 模版... -->


<!-- 目前任务 -->
@if (count($tasks) > 0)
    <div class="panel panel-default">
        <div class="panel-heading">
            目前任务
        </div>

        <div class="panel-body">

            <table class="table table-striped task-table">

                <!-- 表头 -->
                <thead>
                <th>Task</th>
                <th>&nbsp;</th>
                </thead>

                <!-- 表身 -->
                <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <!-- 任务名称 -->
                        <td class="table-text">
                            <div>{{ $task->name }}</div>
                        </td>

                        <td>
                            <form action="{{ url('/task/id/' . $task->id)  }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}

                                <button class="btn btn-primary">删除任务</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection

