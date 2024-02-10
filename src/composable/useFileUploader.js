import { ref, watchEffect } from 'vue'

export function useFileUploader(active) {
    const filePreview = ref(null);
    const filePath = ref(null);
    async function uploadFile(e) {
        filePath.value= e.target.files[0]


        const readData = (f) =>
            new Promise((resolve) => {
                const reader = new FileReader();
                reader.onloadend = () => resolve(reader.result);
                reader.readAsDataURL(f);
            });

            filePreview.value = await readData(filePath.value);
    }

    watchEffect(() => {
        if (active.value == false) {
            filePreview.value = 'images/no-image.jpg';
        }
    });

    return {
        filePreview,
        filePath,
        uploadFile
    };
}