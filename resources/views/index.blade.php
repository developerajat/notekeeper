<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }} - Create Quick Notes</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sketchpad/0.1.0/scripts/sketchpad.min.js"
        integrity="sha512-GTMvKIuYWnu5y1gGLUbMNIXbxusPHehyCdBZJpv+oPikopcgjWBmsIziyp9N8QlRXRFB9y02mQ0C1MRnelE5tg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        .text-blue {
            color: darkcyan;
        }
    </style>
    @notifyCss
    @laravelPWA
</head>

<body>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <div class="page-content container note-has-grid">
        <ul class="nav nav-pills p-3 bg-white mb-3 align-items-center">
            <li class="nav-item">
                <a href="{{ route('index') }}"
                    class="btn-sm nav-link note-link d-flex align-items-center px-2 px-md-3 mr-0 mr-md-2 {{ request()->segment(1) == '' ? 'active' : '' }}"
                    id="all-category">
                    <i class="icon-layers mr-1"></i><span class="d-md-block">All Notes</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('notes.pinned') }}"
                    class="btn-sm nav-link note-link d-flex align-items-center px-2 px-md-3 mr-0 mr-md-2 {{ request()->segment(1) == 'pinned' ? 'active' : '' }}"
                    id="note-pinned"> <i class="icon-briefcase mr-1"></i><span class="d-md-block">Pinned</span></a>
            </li>
            <li class="nav-item">
                <a href="{{ route('notes.trashed') }}"
                    class="btn-sm nav-link note-link d-flex align-items-center px-2 px-md-3 mr-0 mr-md-2 {{ request()->segment(1) == 'trashed' ? 'active' : '' }}"
                    id="note-social"> <i class="icon-share-alt mr-1"></i><span class="d-md-block">Trashed</span></a>
            </li>
            <li class="nav-item">
                <a href="{{ route('logout') }}" class="btn-sm nav-link d-flex align-items-center px-3" title="Logout"
                    id="logout"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
            </li>
            <li class="nav-item ml-auto">
                <a href="javascript:void(0)" class="btn-sm nav-link btn-primary d-flex align-items-center px-3"
                    id="add-notes" data-toggle="modal" data-target="#addnotesmodal"> <i class="icon-note m-1"></i>
                    <span class="d-md-block font-14">Add Note</span>
                </a>
            </li>
        </ul>
        <div class="tab-content bg-transparent">
            <div id="note-full-container" class="note-has-grid row">
                <div class="txt-center ">
                    <span class="notes-empty"></span>
                </div>
                @forelse ($notes as $note)
                    <div class="col-md-4 single-note-item all-category note-{{ $note->id }}" style="">
                        <div class="card card-body">
                            <span class="side-stick"></span>
                            <h5 class="note-title text-truncate w-75 mb-0"
                                data-noteheading="{{ $note->title ?? '' }}">
                                {{ $note->title ?? '' }}
                            </h5>

                            <p class="note-date font-12 text-muted">
                                {{ date('F d, Y, g:i A', strtotime($note->created_at ?? '')) }}</p>
                            <div class="note-content">
                                <p class="note-inner-content text-muted note-box" id="note-box{{ $note->id }}"
                                    data-notecontent="{!! $note->description ?? '' !!}">
                                    {!! $note->description ?? '' !!}
                                </p>
                            </div>
                            <div class="d-flex align-items-center action-icons">
                                @if ($note->deleted_at)
                                    <span class="mr-3">
                                        <i class="fa fa-undo undo-note" id={{ $note->id }} aria-hidden="true"
                                            title="Undo"></i>
                                    </span>
                                @else
                                    <span class="mr-3">
                                        <i class="fa fa-thumb-tack favourite-note {{ $note->pinned == 1 ? 'text-blue' : '' }}"
                                            id={{ $note->id }} aria-hidden="true"
                                            title="{{ $note->pinned == 1 ? 'Unpin' : 'Pin' }}">
                                        </i>
                                    </span>
                                @endif
                                <a href="javascript:void(0)" class="delete-note"
                                    data-form="deleteForm-{{ $note->id }}" title="Delete">
                                    <span class="mr-3"><i class="fa fa-trash"></i></span>
                                </a>
                                <form id="deleteForm-{{ $note->id }}"
                                    action="{{ request()->segment(1) == 'trashed' ? route('forceDelete', $note->id) : route('destroy', $note->id) }}"
                                    method="post">
                                    @csrf
                                </form>
                                @if (!$note->deleted_at)
                                    <a href="#" title="Copy" data-original-title="Copy" class="copy-note"
                                        note-data="{{ strip_tags($note->description) }}">
                                        <span class="mr-3"><i class="fa fa-files-o" aria-hidden="true"></i></span>
                                    </a>
                                    <a href="javascript:void(0)" class="edit-note" id="editNote"
                                        note-id="{{ $note->id }}" note-title="{{ $note->title }}"
                                        note-desc="{{ strip_tags($note->description) }}" title="Edit">
                                        <span class="mr-1"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                                    </a>
                                @endif
                                @if (count(preg_split('/\n|\r/', $note->description)) / 2 + 0.5 > 3)
                                    <p class="read-more">
                                        <a class='read-more-text' note-id="{{ $note->id }}" href="#">Read
                                            More</a>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="txt-center">
                        No Data found!
                    </div>
                @endforelse

            </div>
            <div class="pagination">
                {{ $notes->links() }}
            </div>
        </div>

        <!-- Modal Add notes -->
        <div class="modal fade" id="addnotesmodal" tabindex="-1" role="dialog"
            aria-labelledby="addnotesmodalTitle" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title text-white">Add Notes</h5>
                        <a class="btn btn-sm" title="Draw Notes" href="javascript:void(0)" id="add-sketch" data-toggle="modal" data-target="#addsketchmodal">
                            <figure class="paint-brush">
                                <img src="{{ asset('images/icons/paint-brush.png') }}" alt="">
                            </figure>
                        </a>
                        <button type="button" class="close text-white" data-dismiss="modal" data-backdrop="static"
                            data-keyboard="false" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="{{ route('store') }}" method="POST" id="addnotesmodalTitle">
                        @csrf
                        <div class="modal-body">
                            <div class="notes-box">
                                <div class="notes-content">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div class="note-title">
                                                <label>Note Title</label>
                                                <input name="title" type="text" id="note-has-title"
                                                    class="form-control" minlength="2" placeholder="Title"
                                                    required />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="note-description">
                                                <label>Note Description</label>
                                                <textarea name="description" id="note-has-description" class="form-control" minlength="5"
                                                    placeholder="Description" rows="7" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="btn-n-save" class="float-left btn btn-success"
                                style="display: none;">Save</button>
                            <button type="submit" class="btn btn-info">Add</button>
                            <button class="btn btn-danger" data-dismiss="modal">Discard</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal Add notes End -->

        <!-- Modal Add sketch -->
        <div class="modal fade" id="addsketchmodal" tabindex="-1" role="dialog"
            aria-labelledby="addnotesmodalTitle" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title text-white">Draw Notes</h5>
                        <a class="btn btn-sm" title="undo" href="javascript:void(0)" onclick="sketchpad.undo();">
                            <figure class="undo-icon">
                                <img src="{{ asset('images/icons/undo.png') }}" alt="">
                            </figure>
                        </a>

                        <a class="btn btn-sm" title="redo" href="javascript:void(0)" onclick="sketchpad.redo();">
                            <figure class="redo-icon">
                                <img src="{{ asset('images/icons/undo.png') }}" alt="">
                            </figure>
                        </a>

                        <a class="btn btn-sm" title="animate" href="javascript:void(0)" onclick="sketchpad.animate(10);">
                            <figure class="animate-icon">
                                <img src="{{ asset('images/icons/animate-icon.png') }}" alt="">
                            </figure>
                        </a>

                        <button type="button" class="close text-white" data-dismiss="modal" data-backdrop="static"
                            data-keyboard="false" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="{{ route('store') }}" method="POST" id="sketchStore">
                        @csrf
                        <div class="modal-body">
                            <div class="notes-box">
                                <div class="notes-content">
                                    <div class="row">
                                        <canvas id="noteArea">

                                        </canvas>
                                        <input type="hidden" id="sketch" name="sketch" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="btn-n-save" class="float-left btn btn-success"
                                style="display: none;">Save</button>
                            {{-- <button type="submit" class="btn btn-info">Save</button> --}}
                            <button class="btn btn-danger clearSketch">Discard</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal Add sketch End -->

        <!-- Modal Add notes -->
        <div class="modal fade" id="editnotesmodal" tabindex="-1" role="dialog"
            aria-labelledby="editnotesmodalTitle" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title text-white">Edit Note</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" data-backdrop="static"
                            data-keyboard="false" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="{{ route('notes.update', ['noteId']) }}" method="POST" id="editNoteForm">
                        @csrf
                        <div class="modal-body">
                            <div class="notes-box">
                                <div class="notes-content">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div class="note-title">
                                                <label>Note Title</label>
                                                <input name="title" type="text" id="noteTitle"
                                                    class="form-control" minlength="2" placeholder="Title"
                                                    required />
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="note-description">
                                                <label>Note Description</label>
                                                <textarea name="description" id="noteDescription" class="form-control" minlength="5" placeholder="Description"
                                                    rows="7" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info">Update</button>
                            <button class="btn btn-danger" data-dismiss="modal">Discard</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal Add notes End -->

    </div>
    <x:notify-messages />
    @notifyJs

    <script>
        var sketchpad = new Sketchpad({
            element: '#noteArea',
            width: 500,
            height: 350,
        });

        $("form#sketchStore").submit(function(e){
            e.preventDefault();
            let sketchData = sketchpad.toJSON();
            console.log(sketchData);

        //     $.ajax({
        //     method: "POST",
        //     url: "{{route('storeSketch')}}",
        //     }).done(function( msg ) {
        //     if(msg.error == 0){
        //         //$('.sucess-status-update').html(msg.message);
        //         alert(msg.message);
        //     }else{
        //         alert(msg.message);
        //         //$('.error-favourite-message').html(msg.message);
        //     }
        // });

        var data = {
            "sketchData": sketchData,
            "__token": "{{csrf_token()}}"
        };
        $.ajax({
            url: "{{route('storeSketch')}}",
            type: "POST",
            data : JSON.stringify(data),
            contentType : 'application/json',
            success: function (data) {
                console.log(data);
                alert(data);
            },
            error: function (data) {
                alert(data);
                console.log(data);

            }
        });

            // $.post("",
            // {
            //     "sketchData": sketchData,
            //     "__token": "{{csrf_token()}}"
            // }, function(data, status){
            //     alert("Data: " + data + "\nStatus: " + status);
            // });

            // $('#sketch').val(sketchpad.toJSON());
            // $(this).submit();
        });

        $('body').on('click', '.clearSketch', function(e) {
            e.preventDefault();
                sketchpad = new Sketchpad({
                element: '#noteArea',
                width: 500,
                height: 350,
            });
            $('#addsketchmodal').modal('toggle');
        });

        $('body').on('click', '.delete-note', function() {
            var formId = $(this).attr('data-form');
            $(`form#${formId}`).submit();
        });

        $('body').on('click', '#add-sketch', function() {
            $("#addnotesmodal").modal('hide');
        });

        $('body').on('click', '.favourite-note', function() {
            let id = $(this).attr('id');
            let isPinned = $(this).hasClass('text-blue');
            var value = isPinned ? 0 : 1;
            var segment = "{{ Request::segment(1) }}";

            if (isPinned) {
                $(this).removeClass('text-blue');
                $(this).attr('title', 'Pin');
            } else {
                $(this).addClass('text-blue');
                $(this).attr('title', 'Unpin');
            }
            let data = {
                "id": id,
                "value": value
            };
            $.get("{{ route('notes.pin') }}", data, function(data, status) {
                console.log('result', data);
                if (segment && segment == "pinned") {
                    $(".note-" + data).remove();
                }
                console.log('length', $('.single-note-item').length);
                if ($('.single-note-item').length == 0) {
                    console.log('here');
                    $('.notes-empty').html("No Data found!");
                }
            });
        });

        $('body').on('click', '.undo-note', function() {
            let id = $(this).attr('id');

            let data = {
                "id": id
            };
            $.get("{{ route('notes.restore') }}", data, function(data, status) {
                if (status == "success") {
                    $(".note-" + data).remove();
                    console.log('length', $('.single-note-item').length);
                    if ($('.single-note-item').length == 0) {
                        console.log('here');
                        $('.notes-empty').html("No Data found!");
                    }
                }
            });
        });

        $('body').on('click', '.read-more-text', function(e) {
            e.preventDefault();
            let id = $(this).attr('note-id');
            $('#note-box' + id).toggleClass('note-box');
            if ($(this).text() == "Read More") {
                $(this).text('Read Less');
            } else {
                $(this).text('Read More');
            }
        });

        $('body').on('click', '.copy-note', function(e) {
            e.preventDefault();
            var copyText = $(this).attr('note-data');

            document.addEventListener('copy', function(e) {
                e.clipboardData.setData('text/plain', copyText);
                e.preventDefault();
            }, true);

            document.execCommand('copy');
            $('.copy-note').attr('data-original-title', 'Copy');
            $(this).attr('title', 'Copied');
            $(this).attr('data-original-title', 'Copied');
            $(this).tooltip("show");
        });

        $('body').on('click', '#editNote', function(e) {
            e.preventDefault();
            $('#editnotesmodal').modal('show');
            var id = $(this).attr('note-id');
            var title = $(this).attr('note-title');
            var desc = $(this).attr('note-desc');
            var route = $('#editNoteForm').attr('action');
            $('#editNoteForm').attr('action', route.replace('noteId', id));
            $('#noteTitle').val(title);
            $('#noteDescription').val(desc);

        });
    </script>
</body>

</html>
