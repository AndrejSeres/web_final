@extends('layouts.app')

@section('content')
<main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.debug.js"></script>

    <body>

        <div class="container alert-info p-3 mt-4" id="textToDownload">
            <div class="row">
                <div class="col-12">
                    <h1>Webová aplikácia Math-ify</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <span>Vitajte na stránke Math-ify. Táto stránka slúži na počítanie
                        rôznych typov príkladov pre študentov, ale aj pre učiteľov,
                        ktorí môžu dané príklady spravovať.</span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h2>Registrácia:</h2>
                </div>
            </div>

            <div class="row text-center mb-4">
                <div class="col-md-12">
                    Registrácia sa líši od role študenta a učiteľa. Pri registrácií je
                    možné zvolliť si rolu študent alebo učiteľ. Na základe toho sa odvíja
                    aj zobrazený obsah stránky.
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h2>Rola učiteľa:</h2>
                    <ol>
                        <li>Učiteľ má možnosť generovania príkladov z ktorých latechových súborov si môžu študenti generovať príklady na riešenie.</li>
                        <li>Ďalej má možnosť uviesť v ktorom období bude možné dané príkaldy generovať.</li>
                        <li>Ďalšie funkcionality role učiteľa:</li>
                        <ol>
                            <li>Učiteľ môže definovať počet bodov koľko može zapísať konkrétnemu študentovi za určitú sadu úloh.</li>
                            <li>Má prístup k tabuľke so štatistikou s informáciami o konkrétnych študentoch -
                                koľko úloh si ktorý študent vygeneroval, koľko ich odovzdal a koľko za ne získal bodov, aké úlohy si ktorý študent vygeneroval, aké odovzdal, odovzdaný výsledok spolu s informáciou, či bol správny a koľko získal za ktorú úlohu bodov.</li>
                        </ol>
                    </ol>
                </div>
                <div class="col-md-6">
                    <h2>Rola Študenta:</h2>
                    <ol>
                        <li>Študent si vie vygenerovať príklady na riešenie.</li>
                        <li>Zároveň si vie vygenerovať prehľad zadaných úloh, kde sa nachádza aj možnosť odovzdania. Avšak každú úlohu je možné odovzdať samostatne.</li>
                        <li>Odovzdanie úlohy spočíva v napísaní odpovede, ktorá bude vo forme matematického výrazu.</li>
                    </ol>
                    <div class="col-md-12 text-center pdfButtonDiv">
                        <button class="btn pdfButton" id="more" onclick="downloadAsPdf()">Stiahnuť pdf</button>
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
