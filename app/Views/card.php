<section id="hero" class="d-flex flex-column justify-content-center">
    <div class="container" data-aos="zoom-in" data-aos-delay="100">
        <h2>Ambil Photo Anda </h2>
        <div>
            <?php
            ini_set("display_errors", 0);
            session_start();
            ?>
            <form action="#" method="post" role="form" class="php-email-form">
                <div class="form-row">
                    <div class="col-md-12 form-group">
                        <input type="text" class="form-control" id="idCard" name="idCard" placeholder="Your ID Card" autofocus="autofocus" data-rule="minlen:4" data-msg="Please enter at least 4 chars" onChange="cardCheck(this.value)" value="<?php echo $_SESSION['kartu_id'] ?>" />
                        <div id="data_pasien"></div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</section>

<?php startPush('scripts'); ?>
<script>
    function cardCheck(id) {
        document.getElementById('data_pasien').innerHTML = '<img src="assets/img/proses.gif">';
        document.getElementById('idCard').disabled = true;
        setTimeout(getCard, 1000);
    }


    function getCard(id) {
        var id = document.getElementById('idCard').value;

        $.ajax({
        url: '/get-card', // URL yang mengarah ke controller
        type: 'POST',
        data: { id: id },
        success: function(response) {
            // Parse JSON response
            var data = JSON.parse(response);

            if (data.error) {
                // Jika ada error
                document.getElementById('data_pasien').innerHTML = data.error;
            } else {
                // Tampilkan data kartu
                var pasienData = `
                    <h3>Nama: ${data.nama}</h3>
                    <p>Alamat: ${data.alamat}</p>
                    <img src="${data.foto}" alt="Foto Pasien">
                `;
                document.getElementById('data_pasien').innerHTML = pasienData;
            }
        },
        error: function() {
            document.getElementById('data_pasien').innerHTML = 'Terjadi kesalahan, coba lagi.';
        },
        complete: function() {
            document.getElementById('idCard').disabled = false;
            document.getElementById("idCard").focus();
        }
    });

        // var url = "kartu_insert.php";
        // var id = document.getElementById('idCard').value;
        // var data = {
        //     id: id
        // };

        // $('#data_pasien').load(url, data, function() {
        //     $('#data_pasien').fadeIn('fast');
        // });

        // document.getElementById('idCard').disabled = false;
        // document.getElementById("idCard").focus();
    }
</script>

<?php endPush('scripts'); ?>