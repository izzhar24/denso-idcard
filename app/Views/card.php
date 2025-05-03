<section id="hero" class="d-flex flex-column justify-content-center">
    <div class="container" data-aos="zoom-in" data-aos-delay="100">
        <h2>Ambil Photo Anda </h2>
        <div class="form-row">
            <div class="col-md-12 form-group">
                <input type="text" class="form-control" id="idCard" name="idCard" placeholder="Your ID Card" autofocus="autofocus" data-rule="minlen:4" data-msg="Please enter at least 4 chars" onChange="cardCheck(this.value)" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onkeyup="if(event.keyCode === 13 && !event.repeat) cardCheck(this.value)" />
                <div id="data_employee"></div>
                <button id="nextBtn" class="d-none btn btn-dark btn-lg mt-2 rounded-pill" disabled>Next</button>
            </div>

        </div>
    </div>
</section>

<?php startPush('scripts'); ?>
<script>
    const result = document.getElementById('data_employee');
    const nextBtn = document.getElementById('nextBtn');
    let employee = {};

    function cardCheck(id) {
        document.getElementById('data_employee').innerHTML = '<img src="assets/img/proses.gif">';
        document.getElementById('idCard').disabled = true;
        $.ajax({
            url: '/get-card',
            type: 'POST',
            data: {
                id: id
            },
            success: function(response) {
                console.log("Response: " + response);
                const data = typeof response === 'string' ? JSON.parse(response) : response;
                console.log("NPK: ", data);
                if (response != null) {
                    nextBtn.classList.remove('d-none');
                    nextBtn.disabled = false;
                    const {id, npk, name} = data;
                    employee = { id, npk, name};
                    result.innerHTML = `<p>NPK: ${npk}</p><h3>Nama: ${name}</h3>`;
                } else {
                    result.innerHTML = `<div class="alert alert-error" role="alert">Data Kosong</div>`;
                }
            },
            error: function(err) {
                console.log("error", err);
                result.innerHTML = `<div class="alert alert-danger mt-2" role="alert">Data Tidak Di temukan</div>`;
            },
        });
    }


    nextBtn.addEventListener('click', function() {
        if (!employee) return;
        fetch("/set-employee", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    employee
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json(); // ✅ parse response sebagai JSON
            })
            .then(data => {
                console.log(data); // ✅ gunakan data di sini
                window.location.href = "/photo";
            })
            .catch(error => {
                console.error('Error:', error);
            });

        // console.log("Gambar yang diseleksi:", selectedImageSrc);
    });
</script>

<?php endPush('scripts'); ?>