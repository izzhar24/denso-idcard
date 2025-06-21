<?php startPush('style') ?>
<style>
    #video {
        position: relative;
        transform: rotate(90deg);
        height: 480px;
        width: 640px;
        overflow: hidden;
        object-fit: cover;
        transition: transform 0.3s;
        border: 2px solid rgb(15, 67, 188);
    }

    #remaining-count {
        top: 0;
        z-index: 10;
        opacity: 0.5
    }

    #photos {
        height: 100%;
        display: flex;
        justify-content: center;
        gap: 10px;
        padding: 25px;
        max-width: 700px;
        margin: auto;
    }

    #photos img {
        width: 15rem;
        aspect-ratio: 3/4;
        object-fit: cover;
        border: 3px solid #ccc;
        border-radius: 10px;
        cursor: pointer;
        transition: transform 0.3s, box-shadow 0.3s, border-color 0.3s;
    }

    #photos img:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        border-color: #00f;
    }

    #photos img.selected {
        border-color: #00f;
        box-shadow: 0 0 15px rgba(42, 42, 241, 0.5);
    }

    #zoomModal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.9);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 999;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    #zoomModal.show {
        display: flex;
        opacity: 1;
    }

    #zoomModal img {
        max-width: 90%;
        max-height: 90%;
        border: 4px solid white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .flash-effect {
        animation: flash 0.2s ease-in-out;
    }

    @keyframes flash {
        from {
            opacity: 0.5;
        }

        to {
            opacity: 1;
        }
    }

    .gap-3>*+* {
        margin-left: 1rem;
    }

    button#snap {
        position: absolute;
        bottom: -105px;
        right: 47%;
    }
</style>
<?php endPush() ?>

<section id="hero" class="d-flex flex-column justify-content-center">
    <div class="container" data-aos="zoom-in" data-aos-delay="100">
        <div id="take-photo" class="d-flex flex-column justify-content-center align-items-center">
            <div id="remaining-count" class="text-white bg-dark px-3 py-1 rounded">Sisa 4 foto lagi</div>
            <!-- WRAPPER UNTUK VIDEO -->
            <!-- <div id="video-wrapper"> -->
            <video id="video" autoplay class="mb-3"></video>
            <!-- </div> -->

            <div class="d-flex justify-content-between align-items-center gap-3 my-3 mx-4" style="top: 60px;">
                <button id="snap" type="button" class="mt-4 btn bg-primary rounded-circle d-flex justify-content-center align-items-center" style="width: 70px; height: 70px;">
                    <i class="icofont-camera icofont-2x text-white"></i>
                </button>
                <!-- <button onclick="rotateVideo()" class="btn bg-primary rounded-circle d-flex justify-content-center align-items-center camera-btn" style="width: 70px; height: 70px;">
                    <i class="icofont-refresh icofont-2x text-white"></i>
                </button> -->
            </div>
            <div id="countdown" style="position: absolute; top: 30%; font-size: 10rem; color: white; opacity: 0.5; transition: opacity 0.3s;" class="text-center d-none"></div>
        </div>
        <div class="d-none flex-column justify-content-center align-items-center" id="photos-container">
            <div id="photos"></div>
            <div class="d-flex justify-content-between align-items-center gap-3 my-3 mx-4">
                <button class="btn bg-primary btn-lg rounded-pill" id="reset">
                    <i class="icofont-ui-reply text-white"></i>
                </button>
                <button class="btn btn-dark btn-lg rounded-pill" disabled id="btnNext"
                    data-toggle="modal"
                    data-target="#confirmPhotoSelected">Next</button>
            </div>
        </div>
        <div id="zoomModal" onclick="closeZoom()">
            <img id="zoomImage" src="" />
        </div>
    </div>
</section>

<!-- Konfirmasi Foto -->
<div class="modal fade" id="confirmPhotoSelected" tabindex="-1" role="dialog" aria-labelledby="confirmPhotoSelectedLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah anda yakin pilih foto ini ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="confirmPhotoSelected()">Ya</button>
            </div>
        </div>
    </div>
</div>

<?php startPush('scripts'); ?>
<script>
    const video = document.getElementById('video');
    const wrapper = document.getElementById('video-wrapper');
    const snap = document.getElementById('snap');
    const photos = document.getElementById('photos');
    const nextBtn = document.getElementById('btnNext');
    const resetBtn = document.getElementById('reset');
    const photosContainer = document.getElementById('photos-container');
    const takePhoto = document.getElementById('take-photo');
    const countdown = document.getElementById('countdown');
    const remainingCountPhoto = document.getElementById('remaining-count');

    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d');
    canvas.width = 320;
    canvas.height = 427;
    let capturedPhotos = [];
    let selectedImageSrc = null;
    let totalPhotos = 4;
    let rotateDeg = 0;

    video.addEventListener('loadedmetadata', () => {
        // Cek apakah orientasi awal perlu disesuaikan
        const isPortrait = video.videoHeight > video.videoWidth;
        if (isPortrait) {
            rotateVideo(); // Atau auto rotate jika perlu
        }
    });

    navigator.mediaDevices.getUserMedia({
            video: true
        })
        .then(stream => video.srcObject = stream)
        .catch(err => alert("Webcam error: " + err.message));

    function updateRemainingText() {
        const remaining = totalPhotos - capturedPhotos.length;
        remainingCountPhoto.innerText = `Sisa ${remaining} foto lagi`;
    }

    function rotateVideo() {
        // Simpan ukuran asli video
        const originalWidth = video.videoWidth || video.clientWidth;
        const originalHeight = video.videoHeight || video.clientHeight;


        // width: 30rem;
        // height: 40.5rem;
        rotateDeg = (rotateDeg + 90) % 360;


        video.style.transform = `rotate(${rotateDeg}deg)`;
        // Swap ukuran wrapper untuk 90°/270°
        // Swap ukuran wrapper untuk 90°/270°
        if (rotateDeg === 90 || rotateDeg === 270) {
            wrapper.style.height = '640px';
            wrapper.style.width = '480px';
            console.log(`Original Width: ${originalWidth}, Original Height: ${originalHeight}`);
        }
        // video.style.width = `${originalHeight}px`;
        // video.style.height = `${originalWidth}px`;
    }
    updateRemainingText();
    snap.addEventListener('click', (e) => {
        e.preventDefault();
        snap.disabled = true;
        if (capturedPhotos.length >= totalPhotos) return;
        // Flash effect
        let count = 5;
        const timer = setInterval(() => {
            countdown.classList.replace('d-none', 'd-flex');
            countdown.innerHTML = (count > 0) ? count : '';
            console.log(count);
            if (count === 0) {
                clearInterval(timer);
                video.classList.add('flash-effect');
                countdown.classList.replace('d-flex', 'd-none');
                updateRemainingText();
                snap.disabled = false;
            }
            count--;
        }, 1000);
        setTimeout(() => video.classList.remove('flash-effect'), 500);

        context.translate(canvas.width / 2, canvas.height / 2);
        context.rotate(Math.PI / 2);
        context.drawImage(video, -canvas.height / 2, -canvas.width / 2, canvas.height, canvas.width);
        context.resetTransform();
        const img = document.createElement('img');
        img.src = canvas.toDataURL('image/jpeg');
        img.alt = "Captured Photo";
        photos.appendChild(img);
        capturedPhotos.push(img.src);

        if (capturedPhotos.length === totalPhotos) {
            snap.disabled = true;
            photosContainer.classList.replace('d-none', 'd-flex');
            takePhoto.classList.replace('d-flex', 'd-none');
            nextBtn.disabled = true;
        }
    });

    photos.addEventListener('click', (e) => {
        if (e.target.tagName === 'IMG') {
            document.querySelectorAll('#photos img').forEach(img => img.classList.remove('selected'));
            e.target.classList.add('selected');
            selectedImageSrc = e.target.src;

            if (capturedPhotos.length === 4) {
                nextBtn.disabled = false;
            }
        }
    });

    resetBtn.addEventListener('click', () => {
        capturedPhotos = [];
        photos.innerHTML = '';
        snap.disabled = false;
        nextBtn.disabled = true;
        selectedImageSrc = null;
        takePhoto.classList.replace('d-none', 'd-flex');
        photosContainer.classList.replace('d-flex', 'd-none');
        remainingCountPhoto.innerText = `Sisa ${totalPhotos} foto lagi`;
    });


    function confirmPhotoSelected() {
        if (!selectedImageSrc) return;

        fetch("/set-photo", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    photo: selectedImageSrc
                })
            })
            .then(res => res.json())
            .then(data => {
                console.log(data);
                $('#confirmPhotoSelected').modal('hide');
                window.location.href = "/choose-background";
            })
            .catch(err => console.error(err));
    }

    // Zoom on double-click
    photos.addEventListener('dblclick', (e) => {
        if (e.target.tagName === 'IMG') {
            const modal = document.getElementById('zoomModal');
            const zoomImg = document.getElementById('zoomImage');
            zoomImg.src = e.target.src;
            modal.classList.add('show');
        }
    });

    function closeZoom() {
        document.getElementById('zoomModal').classList.remove('show');
    }
</script>
<?php endPush(); ?>