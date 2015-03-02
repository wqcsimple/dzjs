/**
 * 聊天互动页面写的ajax
 */
var chat ={
		'sendChat': function(){
			$('#sub-message').on('click', function(e){
				  e.preventDefault();
				  $('#loading').show();
			      $.ajax({
			        url: system.url('chat/create.shtml'),
			        type: 'post',
			        dataType: 'json',
			        data: $('#message').serialize(),
			        success: function(data){
			          if (data.status) {
			            alert(data.message);
			            window.location.reload();
			          }else{
			            alert(data.message);
			          }
			        }
			      });
			    });
		},
		'init': function(){
			this.sendChat();
		}
};