@extends('layouts.app')

@section('content')
    <main>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.debug.js"></script>

        <body>

        <div class="container alert-info p-3 mt-4" id="textToDownload">
            <div class="row">
                <div class="col-12">
                    <h1>{{ __("welcome.title") }}</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <span>{{ __("welcome.text") }}</span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h2>{{ __("welcome.registration") }}</h2>
                </div>
            </div>

            <div class="row text-center mb-4">
                <div class="col-md-12">
                    {{ __("welcome.registration_text") }}
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h2>{{ __("welcome.teacher_role") }}</h2>
                    <ol>
                        <li>{{ __("welcome.teacher_item_1") }}</li>
                        <li>{{ __("welcome.teacher_item_2") }}</li>
                        <li>{{ __("welcome.teacher_functionalities") }}</li>
                        <ol>
                            <li>{{ __("welcome.teacher_subitem_1") }}</li>
                            <li>{{ __("welcome.teacher_subitem_2") }}</li>
                        </ol>
                    </ol>
                </div>
                <div class="col-md-6">
                    <h2>{{ __("welcome.student_role") }}</h2>
                    <ol>
                        <li>{{ __("welcome.student_item_1") }}</li>
                        <li>{{ __("welcome.student_item_2") }}</li>
                        <li>{{ __("welcome.student_item_3") }}</li>
                    </ol>
                    <div class="col-md-12 text-center pdfButtonDiv">
                        <button class="btn pdfButton" id="more" onclick="downloadAsPdf()">{{ __("welcome.download_pdf") }}</button>
                    </div>

                </div>
            </div>
        </div>


        <script>
            function downloadAsPdf() {
                const textToDownload = document.getElementById('textToDownload').textContent;
                const filename = 'text_document.pdf';

                const doc = new jsPDF({

                    unit: "in",

                });
                doc.addFont('arial-unicode-ms.ttf', 'Arial Unicode MS', 'normal');
                doc.setFont('Arial Unicode MS');
                doc.setFontSize(9);
                const splitText = textToDownload.match(/.{1,40}/g); // Split text into chunks of 40 characters
                const formattedText = splitText.join('\n');
                doc.text(textToDownload, 0, 0);
                doc.save(filename);
            }
        </script>

        </body>
    </main>
@endsection
