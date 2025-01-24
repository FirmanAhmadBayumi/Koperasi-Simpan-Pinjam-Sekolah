<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="wrapper">
        <!-- Sidebar -->
        <x-sidebar-admin></x-sidebar-admin>

        <div class="main-panel">
            <!-- Navbar -->
            <x-main-header-admin></x-main-header>

                <!-- Content -->
                <div class="container">
                    <div class="page-inner">
                        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                            <div>
                                <h3 class="fw-bold mb-3">Dashboard Admin</h3>
                                <h6 class="op-7 mb-2">Koperasi SMKN 2 Bandar Lampung</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-md-4">
                                <div class="card card-stats card-round">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-icon">
                                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                                    <i class="fas fa-user-check"></i>
                                                </div>
                                            </div>
                                            <div class="col col-stats ms-3 ms-sm-0">
                                                <div class="numbers">
                                                    <p class="card-category">Ketua Pengurus</p>
                                                    <h4 class="card-title">Teguh Sugiarto, S.Kom.</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="card card-stats card-round">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-icon">
                                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                                    <i class="fas fa-users"></i>
                                                </div>
                                            </div>
                                            <div class="col col-stats ms-3 ms-sm-0">
                                                <div class="numbers">
                                                    <p class="card-category">Total Anggota</p>
                                                    <h4 class="card-title">{{ $totalUsers }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="card card-stats card-round">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-icon">
                                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                                    <i class="fas fa-donate"></i>
                                                </div>
                                            </div>
                                            <div class="col col-stats ms-3 ms-sm-0">
                                                <div class="numbers">
                                                    <p class="card-category">Total Simpanan Pokok & Pinjaman</p>
                                                    <h4 class="card-title">
                                                        {{ 'Rp. ' . number_format($totalKeseluruhan, 0, ',', '.') }}
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Grafik Keuangan Koperasi</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="multipleLineChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-round"
                                    style="height: 395px; position: relative; overflow: hidden;">
                                    <div style="height: 100%; width: 100%; 
                                    background-image: url('assets/img/weather/bg-cuaca.jpg'); 
                                    background-size: cover; 
                                    position: absolute; 
                                    top: 0; 
                                    left: 0; 
                                    filter: blur(1px); 
                                    z-index: 1;">
                                    </div>
                                    <div style="position: relative; z-index: 2; padding: 15px;">
                                        <div class="card-header" style="padding-bottom: 0;">
                                            <div class="card-head-row">
                                                <div class="card-title">
                                                    <h6 id="currentDate"></h6>
                                                    <h3>Kota Bandar Lampung</h3>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-body text-center" style="padding-top: 15px;">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <img id="weatherIcon" alt="Weather Icon" style="width:150px;" />
                                                <div class="text-left" style="margin-left: 15px;">
                                                    <h1 id="temperature"></h1>
                                                    <h3 id="weatherDescription" class="text-center"></h3>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column align-items-center justify-content-center"
                                                style="padding-top: 20px;">
                                                <div class="d-flex justify-content-between" style="width: 250px;">
                                                    <div class="d-flex align-items-center">
                                                        <img src="assets/img/weather/humidity.png" alt=""
                                                            style="width:40px;">
                                                        <div class="d-flex flex-column align-items-center"
                                                            style="margin-left: 5px;">
                                                            <h5 id="weatherHumidity" class="text-center"
                                                                style="margin: 0;"></h5>
                                                            <p class="text-center" style="margin: 0;">Humidity</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <img src="assets/img/weather/wind.png" alt=""
                                                            style="width:40px;">
                                                        <div class="d-flex flex-column align-items-center"
                                                            style="margin-left: 5px;">
                                                            <h5 id="weatherWind" class="text-center" style="margin: 0;">
                                                            </h5>
                                                            <p class="text-center" style="margin: 0;">Wind</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Lokasi Koperasi</div>
                                    <p>SMKN 2 Bandar Lampung</p>
                                </div>
                                <div class="card-body">
                                    <iframe
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1364.4547130584026!2d105.24535378298933!3d-5.364128239866796!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40c535b64632bb%3A0x7774722ca7fc4461!2sSMK%20Negeri%202%20Bandar%20Lampung!5e0!3m2!1sid!2sid!4v1720033567049!5m2!1sid!2sid"
                                        width="600" height="450" style="border: 0; width: 100%" allowfullscreen=""
                                        loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <x-footer></x-footer>
                </div>
        </div>

        <!--   Core JS Files   -->
        <script src="assets/js/core/jquery-3.7.1.min.js"></script>
        <script src="assets/js/core/popper.min.js"></script>
        <script src="assets/js/core/bootstrap.min.js"></script>

        <!-- jQuery Scrollbar -->
        <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

        <!-- Chart JS -->
        <script src="assets/js/plugin/chart.js/chart.min.js"></script>

        <!-- jQuery Sparkline -->
        <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

        <!-- Chart Circle -->
        <script src="assets/js/plugin/chart-circle/circles.min.js"></script>

        <!-- Datatables -->
        <script src="assets/js/plugin/datatables/datatables.min.js"></script>

        <!-- Bootstrap Notify -->
        <script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

        <!-- jQuery Vector Maps -->
        <script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
        <script src="assets/js/plugin/jsvectormap/world.js"></script>

        <!-- Sweet Alert -->
        <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

        <!-- Kaiadmin JS -->
        <script src="assets/js/kaiadmin.min.js"></script>

        <!-- Kaiadmin DEMO methods, don't include it in your project! -->
        <script src="assets/js/setting-demo.js"></script>
        <script src="assets/js/demo.js"></script>
        <script>
            function translateWeatherDescription(description) {
                const translations = {
                    'clear sky': 'cerah',
                    'partly sunny': 'sebagian cerah',
                    'scattered clouds': 'berawan',
                    'partly cloudy': 'sebagian berawan',
                    'broken clouds': 'berawan tebal',
                    'overcast clouds': 'mendung',
                    'light rain': 'gerimis',
                    'rain': 'hujan',
                    'shower rain': 'hujan ringan',
                    'heavy rain': 'hujan lebat',
                    'thunderstorm': 'badai',
                    'mist': 'kabut',
                    'haze': 'berkabut asap',
                    'fog': 'kabut tebal',
                    'few clouds': 'sedikit awan'
                };

                // Mengubah deskripsi menjadi huruf kecil dan menerjemahkannya
                const lowerCaseDescription = description.toLowerCase();
                return translations[lowerCaseDescription] || description;
            }

            function getCustomIcon(description) {
                const iconMapping = {
                    'cerah': 'assets/img/weather/clear-sky.png',
                    'sebagian cerah': 'assets/img/weather/partly-sunny.png',
                    'berawan': 'assets/img/weather/scattered-clouds.png',
                    'sebagian berawan': 'assets/img/weather/partly-cloudy.png',
                    'berawan tebal': 'assets/img/weather/broken-cloud.png',
                    'mendung': 'assets/img/weather/overcast.png',
                    'gerimis': 'assets/img/weather/light-rain.png',
                    'hujan': 'assets/img/weather/rain.png',
                    'hujan ringan': 'assets/img/weather/shower-rain.png',
                    'hujan lebat': 'assets/img/weather/heavy-rain.png',
                    'badai': 'assets/img/weather/thunderstorm.png',
                    'kabut': 'assets/img/weather/mist.png',
                    'berkabut asap': 'assets/img/weather/haze.png',
                    'kabut tebal': 'assets/img/weather/fog.png',
                    'sedikit awan': 'assets/img/weather/few-clouds.png',
                };
                return iconMapping[description] || 'default-icon.png';
            }

            function fetchWeatherData() {
                const apiKey = '98740f4ebc0d63bc0f8ba70090e5a091';
                const city = 'Bandar Lampung';
                const apiUrl = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric`;

                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        const weatherIconElement = document.getElementById('weatherIcon');
                        const temperatureElement = document.getElementById('temperature');
                        const weatherDescriptionElement = document.getElementById('weatherDescription');
                        const weatherHumidityElement = document.getElementById('weatherHumidity');
                        const weatherWindElement = document.getElementById('weatherWind');

                        const temperature = data.main.temp;
                        const translatedDescription = translateWeatherDescription(data.weather[0].description.toLowerCase());
                        const weatherHumidity = data.main.humidity;
                        const weatherWind = data.wind.speed;
                        const customIcon = getCustomIcon(translatedDescription);

                        weatherIconElement.src = customIcon;
                        temperatureElement.innerHTML = `${parseInt(temperature)}°C`;
                        weatherDescriptionElement.innerHTML = translatedDescription;
                        weatherHumidityElement.innerHTML = `${weatherHumidity}%`;
                        weatherWindElement.innerHTML = `${parseInt(weatherWind)}Km/h`;
                    })
                    .catch(error => {
                        console.error('Error fetching weather data:', error);
                    });
            }
            fetchWeatherData();

            function updateDateTime() {
                const now = new Date();

                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];

                const dayName = days[now.getDay()];
                const day = now.getDate();
                const month = months[now.getMonth()];
                const year = now.getFullYear();

                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');

                const currentDate = `${day} ${month}, ${hours}:${minutes} WIB`;
                document.getElementById('currentDate').textContent = currentDate;
            }

            function capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }

            document.addEventListener('DOMContentLoaded', function () {
                // Fetch weather data from an API
                fetchWeatherData();

                // Update the date and time every second
                setInterval(updateDateTime, 1000);

                // Fetch data untuk Multiple Line Chart
                var ctx = document.getElementById("multipleLineChart").getContext("2d");

                fetch('/admin/chart-data')
                    .then(response => response.json())
                    .then(data => {
                        var myMultipleLineChart = new Chart(ctx, {
                            type: "line",
                            data: {
                                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep",
                                    "Oct", "Nov", "Dec"
                                ],
                                datasets: [{
                                    label: "Total Pinjaman",
                                    borderColor: "#1d7af3",
                                    pointBorderColor: "#FFF",
                                    pointBackgroundColor: "#1d7af3",
                                    pointBorderWidth: 2,
                                    pointHoverRadius: 4,
                                    pointHoverBorderWidth: 1,
                                    pointRadius: 4,
                                    backgroundColor: "transparent",
                                    fill: false,
                                    borderWidth: 2,
                                    data: data.pinjaman
                                },
                                {
                                    label: "Total Simpanan Pokok",
                                    borderColor: "#59d05d",
                                    pointBorderColor: "#FFF",
                                    pointBackgroundColor: "#59d05d",
                                    pointBorderWidth: 2,
                                    pointHoverRadius: 4,
                                    pointHoverBorderWidth: 1,
                                    pointRadius: 4,
                                    backgroundColor: "transparent",
                                    fill: false,
                                    borderWidth: 2,
                                    data: data.simpanan
                                }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                legend: {
                                    position: "top"
                                },
                                tooltips: {
                                    bodySpacing: 4,
                                    mode: "nearest",
                                    intersect: 0,
                                    position: "nearest",
                                    xPadding: 10,
                                    yPadding: 10,
                                    caretPadding: 10
                                },
                                layout: {
                                    padding: {
                                        left: 15,
                                        right: 15,
                                        top: 15,
                                        bottom: 15
                                    }
                                },
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true,
                                            maxTicksLimit: 5,
                                            padding: 20
                                        },
                                        gridLines: {
                                            drawTicks: false,
                                            display: false
                                        }
                                    }],
                                    xAxes: [{
                                        gridLines: {
                                            zeroLineColor: "transparent"
                                        },
                                        ticks: {
                                            padding: 20
                                        }
                                    }]
                                }
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching chart data:', error);
                    });
            });
        </script>

</x-layout>