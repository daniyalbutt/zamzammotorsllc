@extends('layouts.app')

@section('content')
    <div class="breadcrumb__area">
        <div class="breadcrumb__wrapper mb-25">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Project Details</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif
        <div class="col-xxl-7 col-xl-7">
            @foreach ($data->discussions as $item)
                <div class="card__wrapper">
                    <div class="project__details-top align-items-center gap-10">
                        <div class="header-user d-flex">
                            <div class="d-flex gap-2">
                                <figure class="img-height-user">
                                    <img src="{{ $item->user->profileImage() }}" alt="">
                                </figure>
                                <div class="mr-3">
                                    <p class="m-0"><strong>{{ $item->user->name }}</strong> </p>
                                    <p class="m-0">{{ $item->created_at->format('h:i A | d F, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="project__details-title mt-2">
                            <div class="project__details-meta d-flex flex-wrap align-items-center g-5">
                                {!! $item->content !!}
                            </div>
                            @if ($item->media->isNotEmpty())
                                <div class="table__wrapper style_two table-responsive mt-10">
                                    <strong>Files</strong>
                                    <table class="table mb-0">
                                        <thead>
                                            <tr class="table__title bg-title">
                                                <th>File</th>
                                                <th>Extension</th>
                                                <th>Uploaded</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table__body">
                                            @foreach ($item->media as $media)
                                                <tr>
                                                    <td>
                                                        <a class="anchor-hover"
                                                            href="{{ asset('storage/' . $media->file_path) }}"
                                                            download>{{ $media->file_name }}</a>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary">
                                                            {{ $media->file_extension }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-black">
                                                            {{ $item->user->name }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        <div class="col-xxl-5 col-xl-5">
            <div class="position-sticky">
                <div class="card__wrapper">
                    <div class="card__body">
                        <form action="{{ route('forums.add-discussion', $data->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="uploaded_files" id="uploaded-files" value="">

                            <div class="from__input-box">
                                <div class="form__input-title">
                                    <label>Content <span>*</span></label>
                                </div>
                                <div class="from__input-box">
                                    <textarea name="content" id="tinymce_simple_textarea"></textarea>
                                </div>
                            </div>

                            <div class="from__input-box">
                                <div class="form__input-title">
                                    <label>Attached files (Max 100MB per file)</label>
                                </div>
                                <div class="from__input-box">
                                    <input id="image-file" type="file" name="images[]" multiple
                                        data-browse-on-zone-click="true" class="form-control">
                                    <div id="upload-progress" class="mt-3" style="display: none;">
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <small class="text-muted">Uploading files...</small>
                                    </div>
                                    <div id="upload-status" class="mt-2"></div>
                                    <small class="text-muted">Select files to upload (Max 100MB per file, up to 20
                                        files)</small>
                                </div>
                            </div>

                            <div class="form__input-box d-flex justify-content-end gap-15">
                                <button type="submit" class="btn btn-primary btn-md" id="submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('css')
        <link rel="stylesheet" href="{{ asset('css/fileinput.css') }}">
        <style>
            .file-drop-zone {
                border: 2px dashed #007bff !important;
                border-radius: 8px !important;
                background-color: #f8f9fa !important;
                min-height: 120px !important;
                padding: 20px !important;
                text-align: center !important;
                transition: all 0.3s ease !important;
            }

            .file-drop-zone:hover {
                border-color: #0056b3 !important;
                background-color: #e3f2fd !important;
            }

            .file-drop-zone.drag-over {
                border-color: #28a745 !important;
                background-color: #d4edda !important;
                transform: scale(1.02) !important;
            }

            .file-drop-zone-title {
                font-size: 16px !important;
                color: #007bff !important;
                font-weight: 500 !important;
                margin-bottom: 10px !important;
            }

            .file-drop-zone-click-title {
                color: #6c757d !important;
                font-size: 14px !important;
            }

            .file-preview {
                border: 1px solid #dee2e6 !important;
                border-radius: 8px !important;
                margin-top: 15px !important;
            }

            .file-preview-frame {
                border: 1px solid #e9ecef !important;
                border-radius: 6px !important;
                margin: 5px !important;
                padding: 10px !important;
                background: #fff !important;
            }

            .file-preview-thumbnail {
                text-align: center !important;
                margin-bottom: 10px !important;
            }

            .file-name {
                font-weight: 500 !important;
                color: #495057 !important;
                word-break: break-word !important;
            }

            .file-size {
                color: #6c757d !important;
                font-size: 12px !important;
            }

            .file-footer-buttons {
                margin-top: 10px !important;
            }

            .btn-file {
                background-color: #007bff !important;
                border-color: #007bff !important;
                color: white !important;
                padding: 8px 16px !important;
                border-radius: 4px !important;
                font-weight: 500 !important;
            }

            .btn-file:hover {
                background-color: #0056b3 !important;
                border-color: #0056b3 !important;
            }

            .kv-upload-progress {
                margin-top: 10px !important;
            }

            .progress {
                height: 8px !important;
                border-radius: 4px !important;
            }

            .progress-bar {
                background-color: #28a745 !important;
            }
        </style>
    @endpush
    @push('js')
        <script src="{{ asset('js/fileinput.js') }}"></script>

        <script>
            let uploadedFiles = [];
            let isUploading = false;

            $(document).ready(function() {
                if (typeof $.fn.fileinput !== 'undefined') {
                    $("#image-file").fileinput({
                        showUpload: false,
                        theme: 'fa',
                        showBrowse: true,
                        showCaption: true,
                        showPreview: true,
                        showRemove: true,
                        showCancel: true,
                        dropZoneEnabled: true,
                        dropZoneTitle: 'Drag & drop files here or click to browse...',
                        dropZoneClickTitle: '<br><small class="text-muted">Click to browse files</small>',
                        overwriteInitial: false,
                        initialPreviewAsData: true,
                        maxFileSize: 104857600,
                        maxFilesNum: 20,
                        allowedFileTypes: ['image', 'video', 'audio', 'text', 'application'],
                        allowedFileExtensions: [
                            'jpg', 'jpeg', 'png', 'gif', 'mp4', 'avi', 'mov', 'mp3', 'wav',
                            'pdf', 'doc', 'docx', 'txt', 'zip', 'rar'
                        ],
                        previewFileType: 'any',
                        browseLabel: 'Browse Files',
                        removeLabel: 'Remove',
                        cancelLabel: 'Cancel',
                        msgPlaceholder: 'Select files to upload...',
                        msgFilesTooMany: 'Number of selected files ({n}) exceeds maximum allowed limit of {m}.',
                        msgFileTooBig: 'File "{name}" ({size} KB) exceeds maximum allowed upload size of {maxSize} KB.',
                        msgInvalidFileType: 'Invalid type for file "{name}". Only "{types}" files are supported.',
                        msgInvalidFileExtension: 'Invalid extension for file "{name}". Only "{extensions}" files are supported.',
                        fileActionSettings: {
                            showRemove: true, // show X button on each file
                            showZoom: true,
                            showDrag: true,
                        }
                    });


                    // Add custom dropzone styling and functionality
                    $('#image-file').on('filebatchselected', function(event, files) {
                        console.log('Files selected:', files.length);
                    });

                    $('#image-file').on('fileloaded', function(event, file, previewId, index, reader) {
                        console.log('File loaded:', file.name);
                    });

                    $('#image-file').on('fileerror', function(event, data, msg) {
                        console.error('File error:', msg);
                        updateUploadStatus('Error: ' + msg, 'error');
                    });

                    // Add drag and drop visual feedback
                    $(document).on('dragover', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    });

                    $(document).on('dragenter', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        $('.file-drop-zone').addClass('drag-over');
                    });

                    $(document).on('dragleave', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        $('.file-drop-zone').removeClass('drag-over');
                    });

                    $(document).on('drop', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        $('.file-drop-zone').removeClass('drag-over');
                    });

                } else {
                    console.warn('Fileinput plugin not loaded, using standard file input');
                    // Fallback: add some basic styling to the file input
                    $('#image-file').addClass('form-control');
                }
            });

            // Handle file selection (fileinput plugin)
            $('#image-file').on('filebatchselected', function(event, files) {
                if (files.length > 0) {
                    uploadFiles(files);
                }
            });

            // Fallback: Handle file selection (standard file input)
            $('#image-file').on('change', function(event) {
                const files = event.target.files;
                if (files.length > 0 && typeof $.fn.fileinput === 'undefined') {
                    // Convert FileList to Array
                    const fileArray = Array.from(files);
                    uploadFiles(fileArray);
                }
            });
            async function uploadFileDirectly(file) {
                const formData = new FormData();
                formData.append('file', file);
                formData.append('name', file.name);
                formData.append('size', file.size);

                const response = await fetch("{{ route('forums.upload') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                return await response.json();
            }
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Update progress bar
function updateProgressBar(percentage) {
    $('.progress-bar').css('width', percentage + '%').attr('aria-valuenow', percentage);
}

// Update upload status
function updateUploadStatus(message, type = 'info') {
    const statusDiv = $('#upload-status');
    const alertClass = type === 'error' ? 'alert-danger' : type === 'success' ? 'alert-success' : 'alert-info';
    statusDiv.html(`<div class="alert ${alertClass} alert-sm">${message}</div>`);
}
            // Chunked file upload function
            async function uploadFiles(files) {
                isUploading = true;
                $('#upload-progress').show();
                $('#submit-btn').prop('disabled', true);
                updateUploadStatus(`Uploading ${files.length} file(s)...`);

                const totalFiles = files.length;
                let completedFiles = 0;

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const uploadId = 'upload_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);

                    try {
                        // Check if file is larger than 1MB and needs chunking
                        const needsChunking = file.size > (1024 * 1024); // 1MB threshold for chunking

                        if (needsChunking) {
                            updateUploadStatus(`Chunking large file: "${file.name}" (${formatFileSize(file.size)})`);
                            const result = await uploadFileInChunks(file, uploadId);
                            if (result.success) {
                                uploadedFiles.push({
                                    file_path: result.file_path,
                                    file_name: result.file_name,
                                    file_size: result.file_size
                                });
                                updateUploadStatus(`Large file "${file.name}" uploaded successfully via chunking`);
                            } else {
                                updateUploadStatus(`Error uploading "${file.name}": ${result.error}`, 'error');
                            }
                        } else {
                            // For small files, use regular upload
                            updateUploadStatus(`Uploading small file: "${file.name}"`);
                            const result = await uploadFileDirectly(file);
                            if (result.success) {
                                uploadedFiles.push({
                                    file_path: result.file_path,
                                    file_name: result.file_name,
                                    file_size: result.file_size
                                });
                                updateUploadStatus(`File "${file.name}" uploaded successfully`);
                            } else {
                                updateUploadStatus(`Error uploading "${file.name}": ${result.error}`, 'error');
                            }
                        }
                    } catch (error) {
                        updateUploadStatus(`Error uploading "${file.name}": ${error.message}`, 'error');
                    }

                    completedFiles++;
                    const progress = (completedFiles / totalFiles) * 100;
                    updateProgressBar(progress);
                }

                // Update hidden input with uploaded files
                $('#uploaded-files').val(JSON.stringify(uploadedFiles));

                isUploading = false;
                $('#upload-progress').hide();
                $('#submit-btn').prop('disabled', false);
                updateUploadStatus(`All files uploaded successfully! (${uploadedFiles.length} files)`, 'success');
            }


            // Upload file in chunks
            async function uploadFileInChunks(file, uploadId) {
                const chunkSize = 5 * 1024 * 1024; // 5MB chunks (adjust as needed)
                const totalChunks = Math.ceil(file.size / chunkSize);

                updateUploadStatus(`Chunking file "${file.name}" into ${totalChunks} parts...`);

                for (let chunk = 0; chunk < totalChunks; chunk++) {
                    const start = chunk * chunkSize;
                    const end = Math.min(start + chunkSize, file.size);
                    const chunkBlob = file.slice(start, end);

                    const formData = new FormData();
                    formData.append('file', chunkBlob);
                    formData.append('chunk', chunk);
                    formData.append('chunks', totalChunks);
                    formData.append('name', file.name);
                    formData.append('size', file.size);
                    formData.append('uploadId', uploadId);

                    // Update progress for individual file
                    const fileProgress = ((chunk + 1) / totalChunks) * 100;
                    updateUploadStatus(`Uploading "${file.name}": ${Math.round(fileProgress)}% complete`);

                    const response = await fetch("{{ route('forums.upload') }}", {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    const result = await response.json();

                    if (!result.success) {
                        throw new Error(result.error || 'Upload failed');
                    }

                    // If this is the last chunk, return the final result
                    if (chunk === totalChunks - 1) {
                        return result;
                    }
                }
            }

            // Update progress bar
            function updateProgressBar(percentage) {
                $('.progress-bar').css('width', percentage + '%').attr('aria-valuenow', percentage);
            }

            // Update upload status
            function updateUploadStatus(message, type = 'info') {
                const statusDiv = $('#upload-status');
                const alertClass = type === 'error' ? 'alert-danger' : type === 'success' ? 'alert-success' : 'alert-info';
                statusDiv.html(`<div class="alert ${alertClass} alert-sm">${message}</div>`);
            }

            // Handle form submission
            $('form').on('submit', function(e) {
                if (isUploading) {
                    e.preventDefault();
                    alert('Please wait for file uploads to complete.');
                    return;
                }

                // Update the form with uploaded files data
                $('#uploaded-files').val(JSON.stringify(uploadedFiles));

                // Get content from TinyMCE
                const content = tinymce.get('tinymce_simple_textarea').getContent();
                $('textarea[name="content"]').val(content);

                // Show loading state
                $('#submit-btn').prop('disabled', true).text('Submitting...');
            });
        </script>
    @endpush
