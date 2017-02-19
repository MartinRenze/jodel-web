		<!-- jQuery, Tether, Bootstrap JS and own-->
		 <script
			  src="https://code.jquery.com/jquery-3.1.1.min.js"
			  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
			  crossorigin="anonymous"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
	    <script src="<?php echo $baseUrl;?>js/jQueryEmoji.js"></script>

		<script>
			//BackButton
			function goBack()
			{
				window.history.back();
			}
		<?php if(isset($includeEmojiAndAjax)){ ?>
			$(document).ready(function()
			{
				//Transform UTF-8 Emoji to img
				$('.jodel > content').Emoji();

				$('a').on('click', function(){
				    $('a').removeClass('selected');
				    $(this).addClass('selected');
				});

				function scrollToAnchor(aid){
				    var aTag = $("article[id='"+ aid +"']");
				    $('html,body').animate({scrollTop: aTag.offset().top-90},'slow');
				}

				<?php if(!isset($_GET['postId']) && !isset($_GET['getPostDetails'])) { ?>

				var win = $(window);
				var lastPostId = "<?php echo $view->lastPostId; ?>";
				var view = "<?php echo $view->view; ?>";
				var hashtag = "<?php echo $view->hashtag; ?>";
				var old_lastPostId = "";
				var morePostsAvailable = true;

				if(window.location.hash)
				{
					var hash = window.location.hash.slice(1);

					if(!$("article[id='"+ hash +"']").length)
					{
						for (var i = 5; i >= 0; i--)
						{
							if(!$("article[id='"+ hash +"']").length)
							{
								$.ajax({
									url: '<?php echo $baseUrl;?>get-posts-ajax.php?lastPostId=' + lastPostId + '&view=' + view + '&hashtag=' + encodeURIComponent(hashtag),
									dataType: 'html',
									async: false,
									success: function(html) {
										var div = document.createElement('div');
										div.innerHTML = html;
										var elements = div.childNodes;
										old_lastPostId = lastPostId;
										lastPostId = elements[3].textContent;
										lastPostId = lastPostId.replace(/\s+/g, '');
										//alert('Neu: ' + lastPostId + " Alt: " + old_lastPostId);
										if(lastPostId == old_lastPostId) {
											
											//morePostsAvailable = false;
										}
										else {
											//alert(elements[3].textContent);
											$('#posts').append(elements[1].innerHTML);
											$('#posts').hide().show(0);
										}
										$('#loading').hide();
									}
								});

								$('.jodel > content').Emoji();
							}
							
						}
						scrollToAnchor(hash);

					}						
				}

				// Each time the user scrolls
				win.scroll(function() {


					// End of the document reached?
					if ($(window).scrollTop() + $(window).height() > $(document).height() - 100 && morePostsAvailable)
					{
						$('#loading').show();

						$.ajax({
							url: '<?php echo $baseUrl;?>get-posts-ajax.php?lastPostId=' + lastPostId + '&view=' + view + '&hashtag=' + encodeURIComponent(hashtag),
							dataType: 'html',
							async: false,
							success: function(html) {
								var div = document.createElement('div');
								div.innerHTML = html;
								var elements = div.childNodes;
								old_lastPostId = lastPostId;
								lastPostId = elements[3].textContent;
								lastPostId = lastPostId.replace(/\s+/g, '');
								//alert('Neu: ' + lastPostId + " Alt: " + old_lastPostId);
								if(lastPostId == old_lastPostId)
								{
									
									//morePostsAvailable = false;
								}
								else
								{
									//alert(elements[3].textContent);
									$('#posts').append(elements[1].innerHTML);
								}
								$('#loading').hide();
							}
						});

						$('.jodel > content').Emoji();
					}
				});
			<?php } ?>
			});	

		<?php } ?>
		</script>

		<?php  
			if(is_file(realpath(__DIR__ . '/..') . '/piwik-script.html'))
			{
			    include(realpath(__DIR__ . '/..') . '/piwik-script.html');
			}
		?>

	</body>
</html>

