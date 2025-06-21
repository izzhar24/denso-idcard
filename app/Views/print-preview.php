<section id="hero" class="d-flex flex-column justify-content-center">
    <div class="container" data-aos="zoom-in" data-aos-delay="100">
        <h2>Print Preview </h2>
        <div class="idcard-container container">
            <img src="<?= asset($background) ?>" alt="img-template" class="idcard-bg">
            <div class="idcard-content">
                <img src="<?= $photo ?>" alt="img-template" class="idcard-photo">
                <div class="idcard-info">
                    <div class="employee-name"><?= $employee['nickname'] ?></div>
                    <div class="employee-npk"><?= $employee['npk'] ?></div>
                </div>
            </div>
            <button onclick="showPrintModal()" class="btn btn-dark btn-print rounded-lg btn-lg">
                <i class="icofont-print"></i>
            </button>
        </div>
    </div>
</section>

<div class="modal fade" id="confirmPrintModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Cetak</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin mencetak ID Card ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="confirmPrint()">Ya, Cetak</button>
            </div>
        </div>
    </div>
</div>

<?php startPush('style') ?>
<style>
    .idcard-container {
        position: relative;
        margin-top: 5rem;
        /* width: 320px;
        height: 500px; */
        /* ID card size */
        height: 85.6mm;
        width: 53.98mm;
        font-family: Verdana, sans-serif;
        scale: calc(1.5);
    }

    .idcard-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
    }

    /* Wrapper foto + info */
    .idcard-content {
        position: absolute;
        top: 20mm;
        left: 35%;
        transform: translateX(-50%);
        text-align: center;
        z-index: 1;
    }

    .idcard-photo {
        width: 25mm;
        height: 35mm;
        /* height: 480px;
        width: 640px; */
        object-fit: cover;
        margin-bottom: 2mm;
    }

    .idcard-info {
        color: black;
        font-size: 7pt;
        font-weight: bold;
    }

    .btn-print {
        position: absolute;
        bottom: -50px;
        left: 80px;
        z-index: 9;
        padding: 5px 10px;
    }
</style>
<?php endPush() ?>

<?php startPush('scripts') ?>
<script>
    function showPrintModal() {
        $('#confirmPrintModal').modal('show');
    }

    function confirmPrint() {
        $('#confirmPrintModal').modal('hide');

        const payload = {
            name: "<?= $employee['nickname'] ?>",
            npk: "<?= $employee['npk'] ?>",
            photo: "<?= $photo ?>",
            bg: "<?= asset($background) ?>"
        };

        console.log(payload);
        // Kirim data ke server untuk disimpan

        $.ajax({
            url: '/store-idcard',
            type: 'POST',
            success: function(response) {
                openPrintWindow(payload);
            },
            error: function(err) {
                console.log("error", err);
            },
        });
    }

    function openPrintWindow(data) {
        const win = window.open('', 'popupPrint', 'width=350,height=550,top=100,left=300');

        win.document.write(`
            <html>
            <head>
                <style>
                body { margin: 0; padding: 0; }
                .idcard-container {
                    width: 53.98mm; height: 85.6mm;
                    font-family: Arial; position: relative;
                }
                .idcard-bg {
                    position: absolute;
                    width: 100%; height: 100%;
                    object-fit: cover;
                }
                .idcard-content {
                    position: absolute;
                    top: 20mm;
                    left: 35%; 
                    transform: translateX(-50%);
                    text-align: center;
                }
                .idcard-photo {
                    width: 25mm; height: 35mm;
                    object-fit: cover; margin-bottom: 2mm;
                }
                .idcard-info {
                    font-size: 3mm;
                    font-weight: bold;
                    color: black;
                }
                @page {
                    size: 53.98mm 85.6mm;
                    margin: 0;
                }
                </style>
            </head>
            <body>
                <div class="idcard-container">
                    <img class="idcard-bg" src="${data.bg}">
                    <div class="idcard-content">
                        <img class="idcard-photo" src="${data.photo}">
                        <div class="idcard-info">${data.name}</div>
                        <div class="idcard-info">${data.npk}</div>
                    </div>
                </div>
            </body>
        </html>
        `);
        win.document.close();

        win.onload = function() {
            win.focus();
            win.print();
            win.close();
            if (win.opener) {
                win.opener.location.href = '/';
            }
        };
    }
</script>
<?php endPush() ?>