<section id="hero" class="d-flex flex-column justify-content-center">
    <div class="container" data-aos="zoom-in" data-aos-delay="100">
        <h2>Ambil Photo Anda </h2>
        <div class="form-row">
            <div class="col-md-12 form-group">
                <input type="text" class="form-control" id="idCard" name="idCard" placeholder="Your ID Card" autofocus="autofocus" data-rule="minlen:4" data-msg="Please enter at least 4 chars" onChange="cardCheck(this.value)" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onkeyup="if(event.keyCode === 13 && !event.repeat) cardCheck(this.value)" />
                <div id="data_employee"></div>
                <button id="nextBtn" class="d-none btn btn-dark btn-lg mt-2 rounded-pill" disabled>Next</button>
                <button onclick="window.location.reload()" id="scanBtn" class="d-none btn btn-primary btn-lg mt-2 rounded-pill">Scan Ulang</button>
            </div>

        </div>
    </div>
</section>

<!-- MOdal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Anda sudah pernah cetak, Apakah ingin mencetak ulang ID Card ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="confirmRequestPrintIdCard()">Ya</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="requestForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <textarea class="form-control" name="reason" id="reason" placeholder="Silahkan masukan alasan anda" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php startPush('scripts'); ?>
<script>
    const result = document.getElementById('data_employee');
    const nextBtn = document.getElementById('nextBtn');
    const scanBtn = document.getElementById('scanBtn');
    const formRequest = document.getElementById('requestForm');
    let employee = {};


    function showModal(type = 'confirm') {
        if (type == 'confirm') {
            $('#confirmModal').modal('show')
        } else {
            $('#requestModal').modal('show');
        }
    }

    function confirmRequestPrintIdCard() {
        $('#confirmModal').modal('hide');
        showModal('request');
    }

    formRequest.addEventListener('submit', function(e) {
        e.preventDefault()
        const reason = document.getElementById('reason');
        if (reason.value.trim() == "") {
            reason.focus();
            return;
        }

        // Submit the form if validation passes
        submitRequestPrintIdCard();

    });

    function submitRequestPrintIdCard() {
        const reason = document.getElementById('reason').value;
        fetch("/request-print-idcard", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: employee.id,
                    reason
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                console.log("data:", data);
                $('#requestModal').modal('hide');

                toastr.success(data.message);
                setTimeout(() => {
                    window.location.href = "/";
                }, 2000)
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

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
                    scanBtn.classList.remove('d-none');
                    nextBtn.disabled = false;
                    const {
                        id,
                        npk,
                        name
                    } = data;
                    employee = {
                        id,
                        npk,
                        name
                    };
                    result.innerHTML = `<p>NPK: ${npk}</p><h3>Nama: ${name}</h3>`;
                } else {
                    result.innerHTML = `<div class="alert alert-error" role="alert">Data Kosong</div>`;
                }
            },
            error: function(err) {
                console.log("error", err);
                scanBtn.classList.remove('d-none');
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
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                console.log("data:", data);
                if (data.exist && !data.status_request) {
                    showModal();
                }else if (data.exist && data.status_request) {
                    toastr.warning('Anda sudah mengajukan request print ulang card id, silahkan hubungi admin');
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 3000)
                } else {
                    window.location.href = "/photo";
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
</script>

<?php endPush('scripts'); ?>