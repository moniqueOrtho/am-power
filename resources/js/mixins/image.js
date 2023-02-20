export default {
    data() {
      return {
        loading: false
      }
    },
    methods: {
        validateImage(file) {
            const labels = this.$store.getters['labels/getTranslations'];
            this.error = "";
            // Is there a file?
            if(!file) {
            this.error = 'noImage' in labels ? labels.noImage : 'No image has been selected!';
            return false;
            }
            // Validate type gif|jpg|png|jpeg|bmp
            let isGood = /\.(?=gif|jpg|png|jpeg|bmp)/gi.test(file.name);
            if(!isGood) {
            this.error = 'noImage' in labels ? labels.noImage : 'No image has been selected!';
            return false;
            }
            // Validate size. Size must be smaller then 2mb
            if(file.size > 2000000) {
            this.error = 'noRightImgSize' in labels ? labels.noRightImgSize : 'The image is larger than 2MB!';
            return false;
            }
            return true;
        },

        deleteUrl(url) {
            URL.revokeObjectURL(url);
        },
        emptyFileInput(refName) {
            this.$refs[refName].value = "";
        },
        async uploadImage(file, refName = '') {
            const config = {
                headers: {
                'content-type': 'multipart/form-data',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            }
            this.loading = true;
            try {
            const newImage = await axios.post('/store_image', file, config);
            this.$store.dispatch('images/storeImage', newImage.data.data);
            this.transformData(newImage.data.data, true);
            } catch (error) {
                console.log(error);
                this.error = error.message
            } finally {
                this.loading = false;
                if(refName !== '') this.emptyFileInput(refName);
            }
        },
      setSuccesMessageImage(message) {
        if (this.errors) this.errors = {};
        if (this.errorMessage) this.errorMessage = "";
        this.alertMessage = message;
      },

      // This function is used to detect the actual image type,
      getMimeType(file, fallback = null) {
        const byteArray = (new Uint8Array(file)).subarray(0, 4);
        let header = '';
        for (let i = 0; i < byteArray.length; i++) {
          header += byteArray[i].toString(16);
        }
        switch (header) {
          case "89504e47":
            return "image/png";
          case "47494638":
            return "image/gif";
          case "ffd8ffe0":
          case "ffd8ffe1":
          case "ffd8ffe2":
          case "ffd8ffe3":
          case "ffd8ffe8":
            return "image/jpeg";
          default:
            return fallback;
        }
      },
      readNewImage(file) {
        const blob = URL.createObjectURL(file);
        // Create a new FileReader to read this image binary data
        const reader = new FileReader();
        // Define a callback function to run, when FileReader finishes its job
        reader.onload = (e) => {
          // Note: arrow function used here, so that "this.imageEdited" refers to the image of Vue component
          this.imageEdited.src = blob;
          this.imageEdited['type'] = this.getMimeType(e.target.result, file.type);
        };
        // Start the reader job - read file as a data url (base64 format)
        reader.readAsArrayBuffer(file);
      },


    }
  }
