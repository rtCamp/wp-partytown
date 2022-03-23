<?php
/**
 * Make POST request from Web Worker Example
 *
 * @package partytown\examples
 */

namespace partytown\examples;

/**
 * POST Request Button
 */
function post_request_btn() {
	echo '<hr>';
	echo '<p id="post-response">POST Request Response</p>';
	echo '<button id="partytown-post-req" class="partytown-button">Make POST Request</button>';
	echo '<hr>';
}
add_action( 'wp_footer', __NAMESPACE__ . '\post_request_btn' );

/**
 * Make POST request from Web Worker
 */
function worker_thread_post_request() {
	?>
	<script type="text/partytown">
		const postRequestBtn = document.getElementById('partytown-post-req');
		const postRequestResponse = document.getElementById('post-response');
		if (postRequestBtn) {
			postRequestBtn.addEventListener('click', async () => {
				fetch('https://jsonplaceholder.typicode.com/posts', {
					method: 'POST',
					body: JSON.stringify({
						title: 'foo',
						body: 'bar',
						userId: 1,
					}),
					headers: {
						'Content-type': 'application/json; charset=UTF-8',
					},
					})
					.then((response) => response.json())
					.then(data=>{
						postRequestResponse.innerHTML = `
							<p>ID: ${data.id}</p>
							<p>Title: ${data.title}</p>
							<p>Body: ${data.body}</p>
							<p>User ID: ${data.userId}</p>
						`;
					})
			});
		}
	</script>
	<?php
}
add_action( 'wp_footer', __NAMESPACE__ . '\worker_thread_post_request' );
