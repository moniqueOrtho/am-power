export default {
    data() {
      return {
        loading: false
      }
    },
    methods: {
        validateImage(file) {
            this.error = "";
            // Is there a file?
            if(!file) {
            this.error = this.$t("No image");
            return false;
            }
            // Validate type gif|jpg|png|jpeg|bmp
            let isGood = /\.(?=gif|jpg|png|jpeg|bmp)/gi.test(file.name);
            if(!isGood) {
            this.error = this.$t("No image");
            return false;
            }
            // Validate size. Size must be smaller then 2mb
            if(file.size > 2000000) {
            this.error = this.$t("Image size restriction");
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
        async uploadImage(file, refName) {
            const config = {
                headers: {
                'content-type': 'multipart/form-data',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            }
            this.loading = true;
            try {
            const newImage = await axios.post('/store_image', file, config);
            this.setNewImage(newImage.data.data);
            } catch (error) {
            console.log(error);

            } finally {
                this.loading = false;
                this.emptyFileInput(refName);

            }
        },
      async deleteImageRequest(obj) {
        let apiRoute, deletedMessage, image, route, id, name, loading;
        route = obj.route;
        id = obj.id;
        name = obj.name;
        loading = obj.loading;
        apiRoute = route + id;
        try {
          deletedMessage = await this.$axios.$delete(apiRoute);
          image.$delete();
          this.setSuccesMessageImage(
            this.$t("succes delete image", [name])
          );
        } catch (error) {
          console.log(error.response);
          if (this.alertMessage) this.alertMessage = "";
          this.errorMessage = error.response.data.message;
        } finally {
          if(loading) loading();
          if(this.deleteloading) this.deleteloading = false;
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
      setNewImage(file) {
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
      imageAction(type) {
        switch (type) {
          case 'edit':
            this.editImage();
            break;
          case 'delete':
            this.deleteImage();
            break;
          case 'maximize':
            this.maximize();
            break;
          case 'stencil-free':
            this.setRestriction();
            break;
          case 'rotate':
            this.rotate = true;
            break;
          case 'reset':
            this.$refs.cropper.reset();
            break;
          case 'flip-horizontal':
            this.flip(true, false);
            break;
          case 'flip-vertical':
            this.flip(false, true);
            break;
          case 'rotate-clockwise':
            this.rotateImage(90);
            break;
          case 'rotate-counter-clockwise':
            this.rotateImage(-90);
            break;
        }
      },
      maximize() {
        const { cropper } = this.$refs;
        const center = {
          left: cropper.coordinates.left + cropper.coordinates.width / 2,
          top: cropper.coordinates.top + cropper.coordinates.height / 2,
        };
        cropper.setCoordinates([
          ({ coordinates, imageSize }) => ({
            width: imageSize.width,
            height: imageSize.height,
          }),
          ({ coordinates, imageSize }) => ({
            left: center.left - coordinates.width / 2,
            top: center.top - coordinates.height / 2,
          }),
        ]);
      },
      setRestriction() {
        this.restrictionType = (this.restrictionType === 'fill-area') ? 'none' : 'fill-area';
      },
      flip(x, y) {
        if (this.$refs.cropper.customImageTransforms.rotate % 180 !== 0) {
          this.$refs.cropper.flip(!x, !y);
        } else {
          this.$refs.cropper.flip(x, y);
        }
      },
      rotateImage(angle) {
        this.$refs.cropper.rotate(angle);
      },

    }
  }
