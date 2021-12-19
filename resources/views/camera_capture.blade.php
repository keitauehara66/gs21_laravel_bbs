<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.9/cropper.min.css">
    <style>

        [v-cloak] {
            display: none;
        }

    </style>
</head>
<body>
<div id="app" v-cloak>

    <!-- ① スマホのカメラを起動する部分 -->
    <div class="p-3" v-if="isStatusReady">
        <label class="btn btn-info">
            &#x1F4F8; 写真を撮ってアップロードする
            <input type="file" class="d-none" accept="image/*" capture="camera" @change="onCaptureImage">
        </label>
    </div>

    <!-- ② 写真撮影後に表示する部分 -->
    <div style="background:#000" v-if="isStatusCropping">
        <div class="text-center text-white font-weight-bold bg-dark p-1"
             style="position:fixed;top:0;width:100%;z-index:10000;opacity:0.8;font-size:80%;">
            画像をドラッグして範囲指定できます
        </div>
        <div class="p-3"
             style="position:fixed;bottom:0;width:100%;z-index:10000;">
            <button type="button" class="btn btn-info btn-lg text-nowrap float-right" @click="onSubmit">アップロードする</button>
            <button type="button" class="btn btn-link btn-lg text-nowrap" @click="onCancel">戻る</button>
        </div>

        <!-- ③ プレビュー画像 -->
        <img ref="preview" style="display:block;max-width:100%;">

    </div>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.12/vue.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.9/cropper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/javascript-canvas-to-blob/3.28.0/js/canvas-to-blob.min.js"></script>
<script>

    new Vue({
        el: '#app',
        data: {
            status: 'ready', // ready -> cropping -> (ready)
            imageFile: null,
            cropper: null
        },
        methods: {
            // ④ 撮影された画像データを取得
            onCaptureImage(e) {

                const files = e.target.files;

                if(files.length > 0) {

                    this.status = 'cropping';
                    this.imageFile = files[0];

                    Vue.nextTick(() => {

                        this.setPreviewImage()

                    });

                }

            },
            // ⑤ 撮影された写真をプレビュー表示
            setPreviewImage() {

                const reader = new FileReader();
                reader.addEventListener('load', () => {

                    this.$refs.preview.src = reader.result;
                    this.setCropper();

                });
                reader.readAsDataURL(this.imageFile);

            },
            // ⑥ Cropperを実行して画像の一部を選択できるようにする
            setCropper() {

                if(this.cropper !== null) {

                    this.cropper.destroy();

                }

                this.cropper = new Cropper(this.$refs.preview, {
                    background: false,
                    zoomable: false,
                    autoCrop: false,
                });

            },
            onCancel() {

                this.cropper.destroy();
                this.status = 'ready';

            },
            // ⑦ 画像をアップロード
            onSubmit() {

                const croppedCanvas = this.cropper.getCroppedCanvas();
                croppedCanvas.toBlob(blob => {

                    const url = '/camera_capture';
                    const formData = new FormData();
                    formData.append('image', blob);

                    axios.post(url, formData)
                        .then(response => {

                            if(response.data.result === true) {

                                alert('アップロードが完了しました');

                            } else {

                                alert('ファイルの保存に失敗しました。');

                            }

                        })
                        .catch(error => {

                            alert(error);

                        });

                }, 'image/jpeg', 0.5);

            }
        },
        computed: {
            isStatusReady() {

                return (this.status === 'ready');

            },
            isStatusCropping() {

                return (this.status === 'cropping');

            },
            isStatusSubmitting() {

                return (this.status === 'submitting');

            }
        },
        mounted() {

            // ⑧ 環境チェック
            const canvas = document.createElement('canvas')

            if(!canvas.toBlob) {

                alert('このブラウザはサポート外です・・・。');

            }

        }
    });

</script>
</body>
</html>
