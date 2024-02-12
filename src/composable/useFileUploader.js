import { ref, watchEffect } from 'vue'
import { v4 as uuidv4 } from 'uuid';
export function useFileUploader(active) {
    const filePreview = ref(null);
    const filePath = ref(null);
    const generateFileName = ref(null);
    async function uploadFile(e) {
        filePath.value= e.target.files[0]

        const readData = (f) =>
            new Promise((resolve) => {
                const reader = new FileReader();
                reader.onloadend = () => resolve(reader.result);
                reader.readAsDataURL(f);
                generateFileName.value = uuidv4();
            });
            
            filePreview.value = await readData(filePath.value);
    }

    watchEffect(() => {
        if (active.value == false) {
            filePreview.value = 'images/no-image.jpg';
            generateFileName.value = null;
        }
    });

    return {
        filePreview,
        filePath,
        uploadFile,
        generateFileName
    };
}