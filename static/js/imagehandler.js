const addPictureButton = document.getElementById('addpicturebutton');
const inputImage = document.getElementById('inputImage');
const imageContainer = document.getElementById('imageContainer');

// Create a persistent DataTransfer to hold selected files
const fileStore = new DataTransfer();

addPictureButton.addEventListener('click', () => {
    if (imageContainer.children.length < 3) {
        inputImage.click();
    } else {
        alert("You can only upload up to 3 images.");
    }
});

inputImage.addEventListener('change', (event) => {
    const newFiles = Array.from(event.target.files);

    const currentCount = fileStore.files.length;
    if (currentCount + newFiles.length > 3) {
        alert("You can only upload up to 3 images.");
        return;
    }

    newFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const box = document.createElement('div');
            box.classList.add('image-box');

            const img = document.createElement('img');
            img.src = e.target.result;

            const btn = document.createElement('button');
            btn.classList.add('deleteButton');
            btn.innerText = 'X';

            const fileIndex = fileStore.files.length; // remember the position

            btn.onclick = () => {
                // Remove from DataTransfer
                const tempFiles = Array.from(fileStore.files);
                tempFiles.splice(fileIndex, 1);

                const newData = new DataTransfer();
                tempFiles.forEach(f => newData.items.add(f));
                fileStore.items.clear();
                for (let i = 0; i < newData.files.length; i++) {
                    fileStore.items.add(newData.files[i]);
                }

                inputImage.files = fileStore.files;
                box.remove();
            };

            box.appendChild(img);
            box.appendChild(btn);
            imageContainer.appendChild(box);
        };

        reader.readAsDataURL(file);
        fileStore.items.add(file); // Add to our persistent file store
    });

    // Sync file input with accumulated files
    inputImage.files = fileStore.files;
});
