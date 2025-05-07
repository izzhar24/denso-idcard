<section id="hero" class="d-flex flex-column justify-content-center">
    <div class="container" data-aos="zoom-in" data-aos-delay="100">
        <h2>PIlih Photo Background Anda </h2>
        <div id="list-background" class="d-flex flex-row justify-content-center align-items-center">
            <?php
            foreach ($templates as $template) {
                echo '<img src="' . asset($template['image_path']) . '" alt="img-template" class="img-template" data-id="'.$template['id'].'" >';
            }
            ?>
        </div>
        <div class="text-center">
            <button class="btn btn-dark btn-lg rounded-pill text-center" disabled id="btnNext">Next</button>
        </div>
    </div>
</section>

<?php startPush('style') ?>
<style>
    #list-background {
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

    #list-background img.selected {
        border-color: #00f;
        box-shadow: 0 0 15px rgba(13, 13, 203, 0.5);
    }

    .img-template {
        width: 12rem;
        aspect-ratio: 3 / 4;
        border: 3px solid #ccc;
        /* Border lebih modern */
        border-radius: 10px;
        /* Sudut membulat */
        cursor: pointer;
        /* Menambah cursor pointer saat hover */
        transition: transform 0.3s, box-shadow 0.3s, border-color 0.3s;
        /* Animasi yang halus */
    }

    img:hover {
        transform: scale(1.05);
        /* Efek zoom saat hover */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        /* Bayangan halus saat hover */
        border-color: #00f;
        /* Mengubah warna border saat hover */
    }
</style>
<?php endPush() ?>

<?php startPush('scripts'); ?>
<script>
    let selectedImageSrc = null;
    let selectedImageId = null;
    const nextBtn = document.getElementById('btnNext');
    document.addEventListener('click', function(e) {
        if (e.target.matches('#list-background img.img-template')) {
            document.querySelectorAll('#list-background img').forEach(img => img.classList.remove('selected'));
            e.target.classList.add('selected');
            selectedImageSrc = e.target.src;
            selectedImageId = e.target.getAttribute('data-id');
            nextBtn.disabled = false;
        }
    });

    nextBtn.addEventListener('click', function() {
        if (!selectedImageSrc) return;

        const image = selectedImageSrc.split("/assets/")[1]

        fetch("/set-background", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    image,
                    selectedImageId
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
                window.location.href = "/print-preview"; // bisa aktifkan jika dibutuhkan
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
</script>
<?php endPush() ?>