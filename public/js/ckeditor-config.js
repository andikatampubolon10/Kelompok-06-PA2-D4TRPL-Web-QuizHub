document.addEventListener('DOMContentLoaded', () => {
    const soalTextarea = document.querySelector('#soal-textarea');
    if (!soalTextarea) return; // Hindari error null

    ClassicEditor.create(soalTextarea, {
        ckfinder: {
            uploadUrl: window.uploadImageUrl, // Dapat dari Blade
        },
        toolbar: [
            'heading', '|', 'bold', 'italic', 'link',
            'bulletedList', 'numberedList', '|',
            'insertTable', 'uploadImage', 'blockQuote',
            'undo', 'redo'
        ],
        image: {
            resizeOptions: [
                { name: 'resizeImage:original', label: 'Asli', value: null },
                { name: 'resizeImage:25', label: '25%', value: '25' },
                { name: 'resizeImage:50', label: '50%', value: '50' },
                { name: 'resizeImage:75', label: '75%', value: '75' }
            ],
            toolbar: [
                'imageTextAlternative', 'toggleImageCaption', '|',
                'imageStyle:inline', 'imageStyle:block', 'imageStyle:side'
            ],
            styles: ['full', 'side', 'alignLeft', 'alignCenter', 'alignRight']
        }
    })
    .catch(error => console.error('CKEditor error:', error));
});
