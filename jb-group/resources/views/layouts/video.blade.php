<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        text-transform: capitalize;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }

    .container {
        min-height: 100vh;
    }

    .container .video-container {
        display: none;
        flex-wrap: wrap;
        gap: 15px;
        padding: 10px;
    }

    .container .video-container, video{
        height: 400px;
        width: 250px;
        border-radius: 5px;
        cursor: pointer;
        overflow: hidden;
    }

    .container .video-container ,video video{
        height: 100%;
        width: 100%;
        object-fit: cover;
        transition: .2s linear;
    }

    .container .popup-video {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 100;
        background: rgba(0,0,0,.8);
        height: 100%;
        width: 100%;
        display: none;
    }

    .container .popup-video video {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 750px;
        border-radius: 5px;
        border: 3px solid #fff;
        object-fit: cover;
    }

    .container .popup-video span {
        position: absolute;
        top: 5px;
        left: 20px;
        font-size: 50px;
        color: #fff;
        font-weight: bolder;
        z-index: 100;
        cursor: pointer;
    }
</style>

<div class="container">
    <div id="video" class="video-container">
        <div class="video">
            @foreach($images as $image)
                @if(str_contains($image->format, 'video'))
                        <video src="/images/{{$image->album->name}}/{{$image->name}}" muted></video>
                @endif
            @endforeach
        </div>
    </div>

    <div class="popup-video">
        <span>&times;</span>
        <video src="/images/test/VID_20191113_102225.mp4" muted autoplay controls></video>
    </div>
</div>

<script>
    document.querySelectorAll('.video-container video').forEach(vid => {
        vid.onclick = () => {
            document.querySelector('.popup-video').style.display = 'block';
            document.querySelector('.popup-video video').src = vid.getAttribute('src');
        }
    })

    document.querySelector('.popup-video span').onclick = () => {
        document.querySelector('.popup-video').style.display = 'none';
    };
</script>
