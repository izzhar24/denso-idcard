<?php startPush('style') ?>
<style>
    #video {
        width: 30rem;
        height: 40.5rem;
        /* 3:4 ratio */
        object-fit: cover;
        border: 2px solid rgb(15, 67, 188);
    }


    #photos {
        height: 100%;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        /* 2 kolom */
        grid-template-rows: repeat(2, auto);
        /* 2 baris */
        gap: 10px;
        padding: 25px;
        /* atas & bawah 40px, kiri & kanan auto */
        max-width: 700px;
        /* opsional: batasi lebar */
        margin-left: auto;
        margin-right: auto;
        justify-items: center;

        /* border: #666 1px solid; */
    }

    #photos img {
        width: 15rem;
        aspect-ratio: 3 / 4;
        object-fit: cover;
        border: 3px solid #ccc;
        /* Border lebih modern */
        border-radius: 10px;
        /* Sudut membulat */
        cursor: pointer;
        /* Menambah cursor pointer saat hover */
        transition: transform 0.3s, box-shadow 0.3s, border-color 0.3s;
        /* Animasi yang halus */
    }

    #photos img:hover {
        transform: scale(1.05);
        /* Efek zoom saat hover */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        /* Bayangan halus saat hover */
        border-color: #00f;
        /* Mengubah warna border saat hover */
    }

    #photos img.selected {
        border-color: #00f;
        box-shadow: 0 0 15px rgba(13, 13, 203, 0.5);
    }

    /* Modal Zoom */
    #zoomModal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 999;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
        /* Animasi modal */
    }

    #zoomModal.show {
        opacity: 1;
    }

    #zoomModal img {
        max-width: 90%;
        max-height: 90%;
        border: 4px solid white;
        border-radius: 10px;
        /* Sudut membulat */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        /* Menambahkan bayangan pada gambar zoom */
    }


    .camera-btn:active {
        transform: scale(0.95);
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
        <div id="result-photo" class="d-none flex-row justify-content-center align-items-center">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <div id="photos"></div>
                <button class="btn bg-primary rounded-circle d-flex justify-content-center align-items-center camera-btn" style="width: 70px; height: 70px; margin: 0 auto;" id="retake">
                    <i class="icofont-ui-reply text-white icofont-2x"></i>
                </button>
            </div>
            <button class="btn btn-dark btn-lg rounded-pill" disabled id="btnNext">Next</button>
        </div>
        <div id="zoomModal" style="display: none;" onclick="closeZoom()">
            <img id="zoomImage" src="" />
        </div>

    </div>
</section>


<?php startPush('scripts'); ?>
<script>
    const video = document.getElementById('video');
    const snap = document.getElementById('snap');
    const photos = document.getElementById('photos');
    const retake = document.getElementById('retake');
    const nextBtn = document.getElementById('btnNext');

    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d');
    canvas.width = 320;
    canvas.height = 427;

    // Start webcam
    navigator.mediaDevices.getUserMedia({
            video: true
        })
        .then(stream => video.srcObject = stream)
        .catch(err => alert("Webcam error: " + err.message));

    // retake click
    retake.addEventListener('click', () => {
        document.getElementById('take-photo').classList.replace('d-none', 'd-flex');
        document.getElementById('result-photo').classList.replace('d-flex', 'd-none');
    });

    // Auto take 4 photos with 1 second delay
    snap.addEventListener('click', () => {
        photos.innerHTML = ''; // clear previous
        document.getElementById('take-photo').classList.replace('d-flex', 'd-none');
        document.getElementById('result-photo').classList.replace('d-none', 'd-flex');
        let count = 0;

        const takePhoto = () => {
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const img = document.createElement('img');
            img.src = canvas.toDataURL('image/jpeg');
            photos.appendChild(img);

            count++;
            if (count < 4) {
                setTimeout(takePhoto, 500); // wait 1s before next
            }
        };

        takePhoto();
    });

    let selectedImageSrc = null;

    document.addEventListener('click', function(e) {
        if (e.target.matches('#photos img')) {
            document.querySelectorAll('#photos img').forEach(img => img.classList.remove('selected'));
            e.target.classList.add('selected');
            selectedImageSrc = e.target.src;
            nextBtn.disabled = false;
        }
    });

    nextBtn.addEventListener('click', function() {
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
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json(); // ✅ parse response sebagai JSON
            })
            .then(data => {
                console.log(data); // ✅ gunakan data di sini
                window.location.href = "/choose-background";
            })
            .catch(error => {
                console.error('Error:', error);
            });
        
        // console.log("Gambar yang diseleksi:", selectedImageSrc);
    });

    // Klik 2x untuk zoom
    document.addEventListener('dblclick', function(e) {
        if (e.target.matches('#photos img')) {
            const zoomModal = document.getElementById('zoomModal');
            const zoomImage = document.getElementById('zoomImage');
            zoomImage.src = e.target.src;
            zoomModal.style.display = 'flex';
        }
    });

    function closeZoom() {
        document.getElementById('zoomModal').style.display = 'none';
    }
</script>
<?php endPush(); ?>