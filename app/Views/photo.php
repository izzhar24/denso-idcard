<?php startPush('style') ?>
<style>
    #video {
        width: 30rem;
        height: 40.5rem;
        object-fit: cover;
        border: 2px solid rgb(15, 67, 188);
    }

    #photos {
        height: 100%;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-template-rows: repeat(2, auto);
        gap: 10px;
        padding: 25px;
        max-width: 700px;
        margin: auto;
        justify-items: center;
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
</style>
<?php endPush() ?>

<section id="hero" class="d-flex flex-column justify-content-center">
    <div class="container" data-aos="zoom-in" data-aos-delay="100">
        <div id="take-photo" class="d-flex flex-column justify-content-center align-items-center">
            <video id="video" autoplay class="mb-3"></video>
            <button id="snap" type="button" class="btn bg-primary rounded-circle d-flex justify-content-center align-items-center camera-btn" style="width: 70px; height: 70px;">
                <i class="icofont-camera icofont-2x text-white"></i>
            </button>
        </div>
        <div class="d-none flex-column justify-content-center align-items-center" id="photos-container">
            <div id="photos"></div>
            <div class="d-flex justify-content-between gap-3 my-3 mx-4">
                <button class="btn bg-primary btn-lg rounded-pill" id="reset"><i class="icofont-ui-reply text-white icofont-2x"></i></button>
                <button class="btn btn-dark btn-lg mt-2 rounded-pill" disabled id="btnNext">Next</button>
            </div>
        </div>
        <div id="zoomModal" onclick="closeZoom()">
            <img id="zoomImage" src="" />
        </div>
    </div>
</section>

<?php startPush('scripts'); ?>
<script>
    const video = document.getElementById('video');
    const snap = document.getElementById('snap');
    const photos = document.getElementById('photos');
    const nextBtn = document.getElementById('btnNext');
    const resetBtn = document.getElementById('reset');
    const photosContainer = document.getElementById('photos-container');
    const takePhoto = document.getElementById('take-photo');

    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d');
    canvas.width = 320;
    canvas.height = 427;
    let capturedPhotos = [];
    let selectedImageSrc = null;

    navigator.mediaDevices.getUserMedia({
            video: true
        })
        .then(stream => video.srcObject = stream)
        .catch(err => alert("Webcam error: " + err.message));

    snap.addEventListener('click', () => {
        if (capturedPhotos.length >= 4) return;

        // Flash effect
        video.classList.add('flash-effect');
        setTimeout(() => video.classList.remove('flash-effect'), 200);

        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        const img = document.createElement('img');
        img.src = canvas.toDataURL('image/jpeg');
        img.alt = "Captured Photo";
        photos.appendChild(img);
        capturedPhotos.push(img.src);

        if (capturedPhotos.length === 4) {
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
    });

    nextBtn.addEventListener('click', () => {
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
                window.location.href = "/choose-background";
            })
            .catch(err => console.error(err));
    });

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