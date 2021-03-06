@extends('layouts.management2')

@section('content')
	<div class="row mb-5">
		<div class="col">
			<h2 class="section-title mb-2">
				@if(strtolower($resource->type) == 'collection')
					Edit Collection
				@else
					@if($resource->collection->isEmpty())
						Edit Resource
					@else
						Edit Resource From Collection: {{$resource->collection->first()->title}}
					@endif
				@endif
				
			</h2>
		</div>
		<div class="col text-right">
			@if($resource->collection->isEmpty())
				<a class="btn btn-primary btn-back mb-2" href="{{url('/resource_management')}}">Back</a>
			@else
				<a class="btn btn-primary btn-back mb-2" href="{{url('/resource_management/'.$resource->collection->first()->resourceid.'/edit')}}">
					Back to Collection
				</a>
			@endif
		</div>
	</div>
	<div class="row justify-content-between pb-1">
		<div class="col-md-8 col-xxl-wide">
			<ul class="nav wizard">
				<li class="nav-item">
					<a class="nav-link wizard-navs" href="#step-1" id="wizard-nav-1"><span class="circle">1</span> Describe the resource</a>
				</li>
				<li class="nav-item">
					<a class="nav-link wizard-navs" href="#step-2" id="wizard-nav-2"><span class="circle">2</span> Create the content</a>
				</li>
				<li class="nav-item">
					<a class="nav-link wizard-navs" href="#step-3" id="wizard-nav-3"><span class="circle">3</span> Classify your resource</a>
				</li>
			</ul>
<form class="form-resource" id="create_resource_form" method="post" action='{{url("/resource_management")}}'>
			@method('POST')
            @csrf
            <input type="hidden" name="mediatype" value="text" id="frmmediatype" />
            <input type="hidden" name="resourceid" value="{{$resource->resourceid}}" id="resourceid" />
            <input type="hidden" name="resourceType" value="{{$resource->type}}">
            <input type="hidden" name="collection" value="{{($resource->collection->isEmpty()) ? '' : $resource->collection->first()->resourceid }}"/>
            <input type="hidden" name="save_and_view" id="save_and_view" value="">
            
			<div class="wizard-content" id="wizard-step-1">
				<h3 class="fs-26 text-primary font-weight-semibold">Describe the resource</h3>
				<p class="mb-2">Provide some information about your resource so other people can find it.</p>
					<div class="form-group">
						<label class="label-primary" for="resourceTitleInput">Enter Resource Title</label>
						<input class="form-control form-control-md summary-input" id="resourceTitleInput" type="text" name="title" placeholder="Resource title..." value="{{$resource->title}}">
					</div>
					<hr>
					<div class="form-group">
						<label class="label-primary" for="resourceDescriptionInput">Enter Resource Description</label>
						<textarea class="form-control form-control-md no-resize summary-input" id="resourceDescriptionInput" rows="8" cols="10" name="description">{{$resource->description}}</textarea>
					</div>
			</div>
			<div class="wizard-content" id="wizard-step-2">
				<h3 class="fs-26 text-primary font-weight-semibold">Create the Content</h3>
				<p class="mb-2">Compose the main content of your resource here. Feel free to use text, images, videos and links to outside resources.</p>

					<div class="form-group">
						<textarea class="form-control form-control-md no-resize" id="elm1" rows="18" cols="10" name="content">{{$resource->content}}</textarea>
					</div>

			</div>
			<div class="wizard-content" id="wizard-step-3">
				<h3 class="fs-26 text-primary font-weight-semibold">Classify your resource</h3>
				<p class="mb-2">Fill in these options to properly classify your resource in the system.</p>
		            @if($resource->collection->isEmpty())
						<div class="form-group">
							<label class="label-primary">Group</label>
							<select class="form-control js-select summary-input" name="group" id="group">
	                            @foreach ($user->getAllGroups() as $group)
	                            <option value="{{ $group->id }}">
	                                {{ $group->name }}
	                            </option>
	                            @endforeach
							</select>
						</div>
						<hr>
		            @else
		                <input type="hidden" name="group" value="{{$resource->collection()->first()->groups()->first()->id}}">
		            @endif

					<div class="form-group">
						<label class="label-primary">Education Levels</label>
						<select class="form-control js-select summary-input" multiple="multiple" name="education_levels[]" id="levels">
                            @foreach ($levels as $level)
                            <option value="{{ $level->id }}" {{ ( in_array($level->id, $selectedLevelGroupings)) ? 'selected' : '' }}>
                                {{ $level->display_name }}
                            </option>
                            @endforeach
						</select>
					</div>
					<hr>
					<div class="form-group">
						<label class="label-primary">Subject Area</label>
						<select class="form-control js-select summary-input" multiple="multiple" name="areas[]" id="areas">
                            @foreach ($subjects as $subject)
                            <option value="{{ $subject->subjectid }}" {{ ( in_array($subject->subjectid, $selectedSubjects)) ? 'selected' : '' }}>
                                {{ $subject->displayname }}
                            </option>
                            @endforeach
						</select>
					</div>
					<hr>
					<div class="form-group">
						<label class="label-primary" for="keywords">Keywords</label>
						<input class="form-control form-control-md summary-input" id="keywords" type="text" placeholder="Keywords..." name="keywords" value="{{$resource->keywords}}">
					</div>

			</div>
			<div class="form-group buttonpane text-right pt-1 mb-2 mb-md-0">
				<a class="btn btn-mute wizard-btns" id="wizard-btn-cancel" href="{{url('/resource_management')}}">Cancel</a>
				<button class="btn btn-primary btn-back wizard-btns" type="button" id="wizard-btn-previous">Previous</button>
				<button class="btn btn-primary btn-next wizard-btns" type="button" id="wizard-btn-next">Next</button>
				<div class="btn-group wizard-btn-save-group wizard-btns">
					<button type="button" class="btn btn-primary  wizard-btn-save">Save</button>
					<button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<span class="sr-only">Toggle Dropdown</span>
					</button>
					<div class="dropdown-menu">
						<a class="dropdown-item wizard-btn-save" href="#">Save</a>
						<a class="dropdown-item wizard-btn-save wizard-btn-save-view" href="#">Save & View</a>
					</div>
				</div>
			</div>
		</div>
</form>		
		<div class="col-md-4 col-xxl-narrow">
			<div class="side-block">
				<h3 class="side-title">Resource Summary <a href="#" class="btn btn-primary expand-sidebar-btn pull-right" id="expandSummaryBtn">+</a></h3>
				<ul class="list-unstyled" id="summaryList">
					<li id="summary-step-1" class="summary-steps">
						<h4>Step 1</h4>
						<span class="text-label text-mute">Resource Type:</span> <span id="summary-resourcetype"></span> <br>
						<span class="text-label text-mute">Resource Title:</span> <span id="summary-title"></span> <br>
						<span class="text-label text-mute">Description:</span> 
						<p><span id="summary-description"></span></p>
					</li>
					<li id="summary-step-2" class="summary-steps">
						<h4>Step 2</h4>
						<span class="text-label text-mute">Fill in the content for your resource.</span>
					</li>
					<li id="summary-step-3" class="summary-steps">
						<h4>Step 3</h4>
						<span class="text-label text-mute">Education Levels:</span><br>
						<ul id="summary-levels">
						</ul>
						<span class="text-label text-mute">Subject Areas:</span><br>
						<ul id="summary-areas">
						</ul>
						<span class="text-label text-mute">Keywords:</span> <span id="summary-keywords"></span>
					</li>
				</ul>
				@if(strtolower($resource->type) == 'collection')
					<h3 class="collection-elements side-title">Collection Contents  <a href="#" class="btn btn-primary expand-sidebar-btn pull-right" id="expandRscBtn">+</a></h3>
					<div id="subResourcesListContainer">
						<div class="row"><div class="col text-right"><a href="{{url('/resource_management/create?collection='.$resource->resourceid)}}" class="btn btn-primary">Add Resource</a></div></div>
						<ul class="mt-2" id="subResourcesList">
							@foreach($resource->sub_resources()->orderBy('displayseqno', 'asc')->get() as $sub)
								<li class="collection-sub-resource">
									<div class="row">
										<div class="col">
											{{trim($sub->title)}}
											@if(strtolower($sub->type) == 'collection')
												<span class="badge badge-info pull-right">Collection</span>
											@endif
										</div>
									</div>
									<div class="row mt-2 sub-resource-controls" style="display:none;">
										<div class="col text-right">
											<a href="{{url('/resource_management/'.$sub->resourceid.'/edit')}}" class="btn btn-secondary" title="View Resource"><i class="fa fa-eye" aria-hidden="true"></i></a>
											<a href="#" class="btn btn-secondary delete-sub-button" title="Delete Resource" subid="{{ $sub->resourceid }}"><i class="fa fa-trash" aria-hidden="true"></i></a>
										</div>
									</div>
								</li>
							@endforeach
						</ul>
					</div>
				@endif
			</div>						
		</div>
	</div>
	<div id="deleteSubModal" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Delete Resource</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to delete this resource?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<form id="deleteResourceForm" method="POST" action="">
						@method('delete')
						@csrf
						<button type="submit" class="btn btn-danger">Delete</button>
					</form>	
				</div>
			</div>
		</div>
	</div>
@endsection

@section('styles')
	<style type="text/css">
		#subResourcesList {
			list-style: none;
		}

		#subResourcesList li {
			cursor: pointer;
			padding: 1rem;
		}

		#subResourcesList li:hover {
			background-color: LightGray;
			border: 4px solid Gainsboro;
		}

		.wizard-btn-save-group button {
			margin-right: 0px !important;
			min-width: 0px !important;
		}
	</style>
@endsection
@section('scripts')
<script src="{{asset('js/tinymce_4.3.2_jquery/tinymce.min.js')}}"></script>
<script type="text/javascript">
	var currentStep = 0;
	var CURRIKI_API_URL = "{{ env('CURRIKI_API_URL') }}";
	var baseDeleteUrl = "{{ url('/resource_management') }}";

	$(function(){
		// Initializing wizard
		currentStep = 1;
		$('.wizard-content, .summary-steps, .wizard-btns').hide();
		$('#wizard-step-1, #summary-step-1, #wizard-btn-cancel, #wizard-btn-next').show();
		$('.wizard-navs').removeClass('active');
		$('#wizard-nav-1').addClass('active');
		$('.js-select').select2({width: '100%'});
		
		// Hooking events
		$('#wizard-btn-next').on('click', nextPage);
		$('#wizard-btn-previous').on('click', previousPage);
		$('.wizard-btn-save').on('click', submitForm);
		$('#wizard-nav-1').on('click', () => { gotoPage(1); });
		$('#wizard-nav-2').on('click', () => { gotoPage(2); });
		$('#wizard-nav-3').on('click', () => { gotoPage(3); });
		$('.summary-input').on('change', summaryInputChanged);
		$('.expand-sidebar-btn').on('click', toggelSideBarSections);
		$('.collection-sub-resource').on('click', (e) => { 
			$('.sub-resource-controls').hide(); 
			$(e.currentTarget).find('.sub-resource-controls').show(); 
		});
		$('.delete-sub-button').on('click', (e) => {
			var id = $(e.currentTarget).attr('subid');
			$('#deleteResourceForm').attr('action', baseDeleteUrl + '/' + id);
			$('#deleteSubModal').modal();
		});

		toggelSideBarSections();
		summaryInputChanged();
		MCEinit();
	});

	function nextPage(){
		if(currentStep == 3)
			return;
		currentStep++;
		refreshStep();
	}

	function previousPage(){
		if(currentStep == 1)
			return;
		currentStep--;
		refreshStep();
	}

	function submitForm(){
		if($(this).hasClass('wizard-btn-save-view'))
			$('#save_and_view').val('true');
		$('#create_resource_form').submit();
	}

	function gotoPage(n){
		currentStep = n;
		refreshStep();
	}

	function toggelSideBarSections(e){
		if(e)
			var id = $(e.currentTarget).attr('id');
		else
			var id = null;

		if(id == 'expandSummaryBtn' || id == null){
			$('#summaryList').show();
			$('#subResourcesListContainer').hide();
			$('#expandSummaryBtn').html('-')
			$('#expandRscBtn').html('+')
		} else {
			$('#summaryList').hide();
			$('#subResourcesListContainer').show();
			$('#expandSummaryBtn').html('+')
			$('#expandRscBtn').html('-')
		}
	}

	function refreshStep(){
		$('.wizard-content, .summary-steps, .wizard-btns').hide();
		$('.wizard-navs').removeClass('active');

		$('#wizard-step-'+currentStep).show();
		if(currentStep == 1){
			$('#summary-step-1, #wizard-btn-cancel, #wizard-btn-next').show();
			$('#wizard-nav-1').addClass('active');
		} else if (currentStep == 2){
			$('#summary-step-1, #summary-step-2, #wizard-btn-cancel, #wizard-btn-next, #wizard-btn-previous').show();
			$('#wizard-nav-1, #wizard-nav-2').addClass('active');
			tinymce.execCommand('mceFocus',false,'elm1');
		} else if (currentStep == 3){
			$('#summary-step-1, #summary-step-2, #summary-step-3, #wizard-btn-cancel, .wizard-btn-save-group, #wizard-btn-previous').show();
			$('#wizard-nav-1, #wizard-nav-2, #wizard-nav-3').addClass('active');
		}

	}

	function summaryInputChanged(){
		// Step 1
		var description = $("#resourceDescriptionInput").val();
		description = (description.length < 64) ? description : description.substring(0,64)+'...';
		$('#summary-resourcetype').html($("input[name='resourceType']").val());
		$('#summary-title').html($("#resourceTitleInput").val());
		$('#summary-description').html(description);

		// Step 3
		var keywords = $("#keywords").val();
		keywords = (keywords.length < 64) ? keywords : keywords.substring(0,64)+'...';
		$('#summary-keywords').html(keywords);
		$('#summary-levels').empty();
		$('#levels').val().forEach((item) => { $('#summary-levels').append('<li>'+$('#levels option[value="'+item+'"]').html().trim()+'</li>'); });
		$('#summary-areas').empty();
		$('#areas').val().forEach((item) => { $('#summary-areas').append('<li>'+$('#areas option[value="'+item+'"]').html().trim()+'</li>'); });

	}

	function MCEinit(){
		var trusted = '{{ $user->trusted }}';

        tinymce.init({
            setup: function(ed) {
                ed.on('change', function(e) {
                    //           console.log('the event object ', e);
                    //           console.log('the editor object ', ed);
                    //           console.log('the content ', ed.getContent());
                });
            },
            language: "en",
            selector: "textarea#elm1",
            theme: "modern",
            width: '99.5%',
            height: '600',
            subfolder: "",
            enableLodeStar: trusted,
            relative_urls: false,
            statusbar: false,
            extended_valid_elements: 'a[accesskey|charset|class|contenteditable|contextmenu|coords|dir|download|draggable|dropzone|hidden|href|hreflang|id|lang|media|name|rel|rev|shape|spellcheck|style|tabindex|target|title|translate|type|onclick|onfocus|onblur],button[onclick|class|title],pre',
            plugins: [
                /*gdocsviewer video*/
                //        oembed
                /* "noneditable fileuploader quiz", */
                "noneditable fileuploader",
                'advlist autolink lists charmap print preview hr anchor pagebreak spellchecker',
                'searchreplace wordcount visualblocks visualchars fullscreen',
                'insertdatetime nonbreaking save table contextmenu directionality',
                'emoticons paste textcolor colorpicker textpattern imagetools '


                /*
                 "advlist autolink lists charmap print hr anchor pagebreak spellchecker",
                 "searchreplace wordcount visualblocks visualchars fullscreen insertdatetime nonbreaking",
                 "save table contextmenu directionality emoticons template paste textcolor"
                 */
            ],
            content_css: [
                //        'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
                //        baseurl + 'wp-content/themes/genesis-curriki/curriki-customized/css/curriki-custom-style-alpha.css',
                //        baseurl + 'wp-content/themes/genesis-curriki/css/misc.css',
                //        baseurl + 'wp-content/themes/genesis-curriki/css/font-awesome.min.css',
                //        baseurl + 'wp-content/themes/genesis-curriki/style.css',
                //        baseurl + 'wp-content/themes/genesis-curriki/css/legacy.css',
                //        baseurl + 'wp-content/plugins/genesis-connect-for-buddypress/css/buddypress.css',
                //        baseurl + 'wp-content/plugins/bbpress/templates/default/css/bbpress.css',
                //        baseurl + 'wp-content/plugins/buddypress/bp-activity/css/mentions.min.css',
                //        baseurl + 'wp-content/plugins/tablepress/css/default.min.css',
                //        baseurl + 'wp-content/themes/genesis-curriki/js/oer-custom-script/oer-custom-style.css?ver=4.4.2',
                //        baseurl + 'wp-content/themes/genesis-curriki/css/curriki-custom-style.css'
                'https://www.curriki.org/wp-content/themes/genesis-curriki/curriki-customized/css/curriki-custom-style-alpha.css?ver=4.3.1',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/curriki-customized/css/jquery.tooltip.css?ver=4.3.1',
                'https://www.curriki.org/wp-content/plugins/genesis-connect-for-buddypress/css/buddypress.css?ver=4.3.1',
                'https://www.curriki.org/wp-content/plugins/bbpress/templates/default/css/bbpress.css?ver=2.5.8-5815',
                'https://www.curriki.org/wp-content/plugins/buddypress/bp-activity/css/mentions.min.css?ver=2.3.4',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/css/misc.css?ver=4.3.1',
                'https://www.curriki.org/wp-content/plugins/tablepress/css/default.min.css?ver=1.6.1',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/css/font-awesome.min.css?ver=4.3.0',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/style.css?ver=4.3.1',
                'https://www.curriki.org/wp-content/plugins/jetpack/_inc/genericons/genericons/genericons.css?ver=3.1',
                'https://www.curriki.org/wp-content/plugins/jetpack/css/jetpack.css?ver=3.7.2',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/js/fancytree/src/skin-win7/ui.fancytree.css',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/js/fancytree/lib/prettify.css',
                //        'https://www.curriki.org/wp-content/plugins/addthis/css/output.css?ver=4.3.1',
                'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css?ver=4.3.1',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/css/legacy.css?ver=4.3.1',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/js/fancybox_v2.1.5/jquery.fancybox.css?v=2.1.5&ver=4.3.1',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/js/qtip2_v2.2.1/jquery.qtip.min.css?ver=4.3.1',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/js/oer-custom-script/oer-custom-style.css?ver=4.3.1',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/css/curriki-custom-style.css?ver=4.3.1',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/css/legacy.css?ver=4.3.1',
                //        'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/css/questions_tinymce.css?ver=4.3.1',
            ],
            /* toolbar1: "oembed image video gdoc lodestar quiz | embed emoticons insertdatetime | newdocument undo redo |  cut copy paste searchreplace | spellchecker fullscreen print preview visualblocks visualchars|", */
            toolbar1: "oembed image video gdoc lodestar | embed emoticons insertdatetime | newdocument undo redo |  cut copy paste searchreplace | spellchecker fullscreen print preview visualblocks visualchars|",
            toolbar2: "styleselect fontselect fontsizeselect | forecolor backcolor | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | ltr rtl "
        });
	}
</script>
@endsection