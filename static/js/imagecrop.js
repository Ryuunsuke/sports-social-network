const inputImage = document.getElementById('inputImage');
const changepfpButton = document.getElementById('changepfpButton');
const acceptcropButton = document.getElementById('acceptcropButton');
const image = document.getElementById('image');
const cancelcropButton = document.getElementById('cancelcropButton');
let cropper;

cancelcropButton.addEventListener('click', () => {
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    image.style.display = 'none';
    acceptcropButton.style.display = 'none';
    cancelcropButton.style.display = 'none';
    enableScrolling();
});

window.addEventListener('beforeunload', () => {
    enableScrolling();
});

changepfpButton.addEventListener('click', () => {
    inputImage.click(); // Trigger hidden file input click
});

inputImage.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) return;
    const url = URL.createObjectURL(file);

    image.src = url;
    image.style.display = 'block';

    if (cropper) {
        cropper.destroy();
    }

    cropper = new Cropper(image, {
    aspectRatio: 1,             // Optional, for square crop box
    viewMode: 1,
    movable: true,
    zoomable: true,
    scalable: false,
    cropBoxResizable: false,    // Disable resizing
    dragMode: 'move',
    ready() {
        const startofcrop = document.getElementById('startofcrop');
        if (startofcrop) {
            startofcrop.scrollIntoView({ behavior: 'smooth', block: 'start' });
            disableScrolling();
            acceptcropButton.style.display = 'block';
            cancelcropButton.style.display = 'block';
        } else {
            disableScrolling();
            acceptcropButton.style.display = 'block';
            cancelcropButton.style.display = 'block';
        }
        // Set fixed crop box size in pixels (e.g., 300x300)
        const cropBoxData = cropper.getCropBoxData();
        const containerData = cropper.getContainerData();

        const fixedWidth = 300;
        const fixedHeight = 300;

        // Calculate center position for crop box inside container
        const left = (containerData.width - fixedWidth) / 2;
        const top = (containerData.height - fixedHeight) / 2;

        cropper.setCropBoxData({
        left: left,
        top: top,
        width: fixedWidth,
        height: fixedHeight
        });
    }
    });
});

acceptcropButton.addEventListener('click', () => {
    if (!cropper) return alert('Upload an image first!');

    const canvas = cropper.getCroppedCanvas({
        width: 300,
        height: 300,
        fillColor: '#fff',
    });

    canvas.toBlob((blob) => {
        const formData = new FormData();
        formData.append('croppedImage', blob, 'cropped.png');

        fetch('../../routes/pfpprocess.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            enableScrolling();
            cropper.destroy();
            image.style.display = 'none';
            acceptcropButton.style.display = 'none';
            cancelcropButton.style.display = 'none';
            window.location.href = '/templates/profile.php';
        })
        .catch(err => {
            console.error(err);
            enableScrolling();
        });
    }, 'image/png');
});

function disableScrolling() {
    document.body.style.overflow = 'hidden';
    if (typeof lenis !== 'undefined' && lenis) lenis.stop();
}

function enableScrolling() {
    document.body.style.overflow = '';
    if (typeof lenis !== 'undefined' && lenis) lenis.start();
}
