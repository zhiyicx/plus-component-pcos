@section('title')
投稿
@endsection

@extends('pcview::layouts.default')

@section('content')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/cropper/cropper.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/news.css') }}"/>
@endsection

<div class="news_left">
    <div class="release_cont">
        <div class="release_title">
            <input type="hidden" id="news_id" name="id" value="{{$id or 0}}" />
            <input type="text" id="subject-title" name="title" value="{{$title or ''}}" placeholder="请在此输入20字以内的标题"/>
        </div>
        <div class="release_title p_30">
            <input type="text" id="subject-abstract" name="abstract" value="{{$subject or ''}}" placeholder="请在此输入200字以内的文章摘要,不填写默认为文章内容前60字"/>
        </div>
        <div class="release_title p_30 release_cates">
            @if(isset($cates))
                @foreach($cates as $k=>$cate)
                    <span data-cid="{{$cate['id']}}"
                        @if(isset($cate_id))
                            @if($cate['id'] == $cate_id) class="current" @endif
                        @endif
                    >{{$cate['name']}}</span>
                @endforeach
            @endif
            <input type="hidden" name="cate_id" id="cate_id">
        </div>
        <div class="release_place">
            @include('pcview::widgets.markdown', ['height'=>'530px', 'width' => '100%', 'content'=>$content ?? ''])
        </div>
        <div class="release_tags">
            <label for="J-select-tags">请选择标签</label for="J-select-tags">
            <ul class="release_tags_selected" id="J-select-tags"></ul>
            <div class="release_tags_list" id="J-tag-box" style="display: none;">
                @foreach ($tags as $tag)
                    <dl>
                        <dt>{{ $tag->name }}</dt>
                        @foreach ($tag->tags as $item)
                            <dd data-id="{{$item->id}}">{{ $item->name }}</dd>
                        @endforeach
                    </dl>
                @endforeach
            </div>
            <input type="hidden" name="tags" id="tags" />
        </div>
        <div class="release_produce">
            <span class="release_bq" style="display: none;">
                <img src="{{ asset('zhiyicx/plus-component-pc/images/pro.png') }}" /><input placeholder="添加标签，多个标签用逗号分开" />
            </span>
            <span class="ai_face_box">
                <img src="@if (!empty($storage)) {{ $routes['storage'] }}{{$storage}}?w=230&h=163 @else {{ asset('zhiyicx/plus-component-pc/images/pic_upload.png') }} @endif" id="J-image-preview" />
                <div class="ai_upload">
                    <input name="subject-image" id="subject-image" type="hidden" value="{{$storage or 0}}" />
                </div>
            </span>
        </div>
        <div class="release_word">
            <input type="text" id="subject-author" name="subject-author" value="{{$author or ''}}" placeholder="文章作者（选填）" />
        </div>
        <div class="release_word">
            <input type="text" id="subject-from" name="subject-from" value="{{$from or ''}}" placeholder="文章转载至何处（非转载可不填）" />
        </div>
        <div class="release_after">投稿后，我们将在两个工作日内给予反馈，谢谢合作！</div>
        <div class="release_btn">
            {{-- <button type="submit" class="subject-submit button release_a1" data-url="{{ Route('pc:doSavePost',['type'=>2]) }}">存草稿</button> --}}
            <button type="submit" class="subject-submit button release_a2">投稿</button>
        </div>
    </div>
</div>

<div class="right_container">
    <!-- 投稿须知 -->
    <div class="release_right">
        <div class="release_right_title">投稿须知</div>
        <div class="release_right_artic">
            <p>请用准确的语言描述您发布的资讯的主旨</p>
            <p>选择适合的资讯分类, 让您发布的资讯能快速在相应的分类中得到展示.</p>
            <p>详细补充您的咨询内容, 并提供一些相关的素材以供参与者更多的了解您所要表述的资讯思想。</p>
            <p>注：如果您的内容不够正式，为了数据更美观，您的投稿将不会通过；投稿内容一经审核通过，所投递的内容将共所有人可以阅读，并在您发布资讯中进行分享、点赞和评论</p>
        </div>
        {{-- <a href="{{ route('pc:minearc') }}">
            <div class="release_right_bottom">
                我的草稿<span class="release_right_num"><i class="icon iconfont icon-icon07"></i></span>
            </div>
        </a> --}}
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('zhiyicx/plus-component-pc/cropper/cropper.min.js')}}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/module.news.js')}}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/md5.min.js')}}"></script>
    <script type="text/javascript">
        var notice = {
            balance : "{{$notice['balance']}}",
            verified: JSON.parse("{{$notice['verified']}}".replace(/&quot;/g, '"')),
            contribute: JSON.parse("{{$notice['contribute']}}".replace(/&quot;/g, '"')),
            pay_conyribute: "{{$notice['pay_conyribute']}}"
        };


        $('.release_tags').on('click', '>*', function(e){
            e.stopPropagation();
            $('#J-tag-box').toggle();
        });

        $('#J-tag-box dd').on('click', function(e){
            e.stopPropagation();
            var selBox = $('#J-select-tags');
            var tag_id = $(this).data('id');
            var tag_name = $(this).text();
            if (selBox.find('li').hasClass('tag_'+tag_id)) {
                noticebox('标签已存在', 0); return;
            }

            if (selBox.find('li').length > 4) {
                noticebox('标签最多五个', 0); return;
            }

            selBox.append('<li class="tag_'+tag_id+'" data-id="'+tag_id+'">'+tag_name+'</li>');
            selBox.on('click', 'li', function(){
                $(this).remove();
                if (selBox.find('li').length == 0) {
                    $('.release_tags label').show();
                }
            });
            $('.release_tags label').hide();
        });
        $('#J-image-preview').on('click',function(){
            var html = '<div id="model">'
                + '<div class="avatar-container" id="crop-avatar">'
                + '<div class="avatar-upload">'
                + '<input type="hidden" class="avatar-src" name="avatar_src">'
                + '<label for="avatarInput">选择上传图片</label>'
                + '<input type="file" class="avatar-input" id="avatarInput" name="avatar_file">'
                + '</div>'
                + '<div class="avatar-wrapper"></div>'
                + '<div class="save-btn"><span>上传完成记得点击保存按钮</span><button type="button" class="btn btn-primary avatar-save">完成</button></div>'
                + '</div></div>';
            ly.loadHtml(html, '上传封面', '600px', '500px;');
            $(function () {
                'use strict';
                var console = window.console || { log: function () {} };
                function CropAvatar($element) {
                    this.$container = $element;
                    this.$avatarInput = this.$container.find('.avatar-input');
                    this.$avatarWrapper = this.$container.find('.avatar-wrapper');
                    this.$avatarPreview = this.$container.find('.avatar-preview');
                    this.$avatarSave = this.$container.find('.avatar-save');
                    this.init();
                }
                // base64
                function dataURLtoBlob(dataurl) {
                    var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
                        bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
                    while(n--){
                        u8arr[n] = bstr.charCodeAt(n);
                    }
                    return new Blob([u8arr], {type:mime});
                }
                CropAvatar.prototype = {
                    constructor: CropAvatar,
                    support: {
                        fileList: !!$('<input type="file">').prop('files'),
                        blobURLs: !!window.URL && URL.createObjectURL,
                        formData: !!window.FormData
                    },
                    init: function () {
                        this.support.datauri = this.support.fileList && this.support.blobURLs;
                        this.fileUpload = {};
                        this.addListener();
                    },
                    addListener: function () {
                        this.$avatarInput.on('change', $.proxy(this.change, this));
                        this.$avatarSave.on('click', $.proxy(this.click, this));
                    },
                    click: function () {
                        var croppedCanvas = this.$img.cropper('getCroppedCanvas');
                        var blob = dataURLtoBlob(croppedCanvas.toDataURL());
                        blob.name = this.fileUpload.origin_filename;
                        this.$avatarSave.text('上传中...');
                        fileUpload.init(blob, function(img, f, file_id){
                            $('#subject-image').val(file_id);
                            $('#J-image-preview').attr('src', '/api/v2/files/'+file_id+'?w=230&h=163');
                            layer.closeAll();
                        });
                    },
                    change: function () {
                        var files,file;
                        if (this.support.datauri) {
                            files = this.$avatarInput.prop('files');
                            if (files.length > 0) {
                                file = files[0];
                                this.fileUpload.mime_type = file.type;
                                this.fileUpload.origin_filename = file.name;
                                this.fileUpload.hash = '123456'+file.name;
                                if (this.isImageFile(file)) {
                                    if (this.url) {
                                        URL.revokeObjectURL(this.url); // Revoke the old one
                                    }
                                    this.url = URL.createObjectURL(file);
                                    this.startCropper();
                                }
                            }
                        } else {
                            file = this.$avatarInput.val();
                            if (this.isImageFile(file)) {
                                this.syncUpload();
                            }
                        }
                    },
                    startCropper: function () {
                        var _this = this;
                        if (this.active) {
                            this.$img.cropper('replace', this.url);
                        } else {
                            this.$img = $('<img src="' + this.url + '">');
                            this.$avatarWrapper.empty().html(this.$img);
                            this.$img.cropper({
                                aspectRatio: 0, //设置剪裁容器的比例
                                viewMode: 1,
                                preview: this.$avatarPreview.selector, //添加额外的元素（容器）的预览
                            });
                            this.active = true;
                        }
                    },
                    stopCropper: function () {
                        if (this.active) {
                            this.$img.cropper('destroy');
                            this.$img.remove();
                            this.active = false;
                        }
                    },
                    isImageFile: function (file) {
                        if (file.type) {
                            return /^image\/\w+$/.test(file.type);
                        } else {
                            return /\.(jpg|jpeg|png|gif)$/.test(file);
                        }
                    }
                };
                return new CropAvatar($('#crop-avatar'));
            });
        });
        // 资讯分类点击
        $('.release_cates span').on('click', function(e){
            if ($(this).hasClass('current')) {
                $('#cate_id').val('');
                $(this).removeClass('current');
            }else{
                $(this).siblings().removeClass('current');
                $(this).addClass('current');
                var cid  = $(this).data('cid');
                $('#cate_id').val(cid);
            }
        });
    </script>
@endsection
