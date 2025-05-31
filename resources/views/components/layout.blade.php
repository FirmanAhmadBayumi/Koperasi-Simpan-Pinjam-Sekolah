<!DOCTYPE html>
<html lang="en">

    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>{{ $title }}</title>
        <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
        <link rel="icon" href="assets/img/kaiadmin/SMKN2.png" type="image/x-icon" />

        <!-- Fonts -->
        <script src="assets/js/plugin/webfont/webfont.min.js"></script>
        <script>
            WebFont.load({
                google: {
                    families: ["Public Sans:300,400,500,600,700"]
                },
                custom: {
                    families: [
                        "Font Awesome 5 Solid",
                        "Font Awesome 5 Regular",
                        "Font Awesome 5 Brands",
                        "simple-line-icons",
                    ],
                    urls: ["assets/css/fonts.min.css"],
                },
                active: function () {
                    sessionStorage.fonts = true;
                },
            });
        </script>

        <style>
            .sm\\:rounded-lg {
                border-radius: 15px;
            }

            #layoutAuthentication_content {
                width: 100%;
            }

            .card {
                max-width: 100%;
            }
        </style>

        <!-- CSS Files -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
        <link rel="stylesheet" href="assets/css/plugins.min.css" />
        <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
    </head>

    <body>
        {{ $slot }}
        @include('roleAdmin.profilSekolah')

        <!-- Script Modal Error/Sukses -->
        @if ($errors->any() || session('success'))
            <script>
                window.addEventListener('DOMContentLoaded', function () {
                    var modal = new bootstrap.Modal(document.getElementById('kelolaProfilSekolah'));
                    modal.show();
                });
            </script>
        @endif
        
        <!-- Script untuk load dinamis (opsional) -->
        <script>
            function tampilkanModalProfilSekolah() {
                if (!document.getElementById('kelolaProfilSekolah')) {
                    fetch('{{ route('profilSekolah') }}')
                        .then(res => res.text())
                        .then(html => {
                            const div = document.createElement('div');
                            div.innerHTML = html;
                            document.body.appendChild(div);
                            var myModal = new bootstrap.Modal(document.getElementById('kelolaProfilSekolah'));
                            myModal.show();
                        });
                } else {
                    var myModal = new bootstrap.Modal(document.getElementById('kelolaProfilSekolah'));
                    myModal.show();
                }
            }
        </script>
    </body>

</html>