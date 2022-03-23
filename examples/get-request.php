<?php
/**
 * Make GET request from Web Worker Example
 *
 * @package partytown\examples
 */

namespace partytown\examples;

/**
 * GET Request Button
 */
function get_request_btn() {
	echo '<hr>';
	echo '<p id="get-res">GET Request Response</p>';
	echo '<button id="partytown-get-req" class="partytown-button">Make GET Request</button>';
}
add_action( 'wp_footer', __NAMESPACE__ . '\get_request_btn' );

/**
 * Make GET request from Web Worker
 */
function worker_thread_get_request() {
	?>
	<script type="text/partytown">
		const getRequestBtn = document.getElementById('partytown-get-req');
		const getRequestResponse = document.getElementById('get-res');
		if (getRequestBtn) {
			getRequestBtn.addEventListener('click', async () => {
				fetch('https://api.github.com/users/thelovekesh')
					.then(response => response.json())
					.then(data => {
						const { name, login, avatar_url } = data;
						getRequestResponse.innerHTML = `
							<p>Name: ${name}</p>
							<p>Login: ${login}</p>
							<img src="${avatar_url}" />
						`;
					})
					.catch(error => {
						getRequestResponse.innerHTML = error;
					});
			});
		}
	</script>
	<?php
}
add_action( 'wp_footer', __NAMESPACE__ . '\worker_thread_get_request' );
