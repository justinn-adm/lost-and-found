function usernameCheck(id) {
	let timer;
	const input = document.getElementById(id);
	const statusDisplay = document.getElementById(`${id}-status`);
	const field = id;
	statusDisplay.style.display = 'none';

	input.addEventListener('input', () => {

		const value = input.value.trim();

		if (value.length < 1) {
			input.style.border = '';
			statusDisplay.style.display = 'none';
			return;
		}
		clearTimeout(timer);

		timer = setTimeout(() => {
			fetch('check_username.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded'
				},
				body: `field=${encodeURIComponent(field)}&value=${encodeURIComponent(value)}`
			})
				.then(res => res.json())
				.then(data => {
					if (data.available) {
						input.style.border = '5px solid #19e34f';
						statusDisplay.style.display = 'none';
					} else {
						input.style.border = '5px solid red';
						statusDisplay.style.padding = 0;
						statusDisplay.innerHTML = `${field} already taken`;
						statusDisplay.style.display = 'block';
						statusDisplay.style.fontWeight = 'bold';
						statusDisplay.style.color = 'red';
					}
				})
				.catch(error => {
					console.log(error);
				});
		}, 500);
	});

	input.addEventListener('blur', () => {
		const value = input.value.trim();

		if (value.length < 1) {
			input.style.border = '';
			statusDisplay.style.display = 'none';
			return;
		}
	});
}

usernameCheck('username');
usernameCheck('email');
