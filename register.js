let usernameValid = false;
let emailValid = false;

document.addEventListener("DOMContentLoaded", () => {
	usernameCheck('username');
	usernameCheck('email');

});

function checkFormValidity() {
	const btn = document.getElementById('submit-btn');
	if (usernameValid && emailValid) {
		btn.style.opacity = 1;
	} else {
		btn.style.opacity = 0;
	}
}

function usernameCheck(id) {
	let timer;
	const input = document.getElementById(id);
	const field = id;
	const statusDisplay = document.getElementById(`${id}-status`);
	statusDisplay.style.display = 'none';

	input.addEventListener('input', () => {
		const value = input.value.trim();

		if (value.length < 1) {
			input.style.border = '';
			statusDisplay.style.display = 'none';
			usernameValid = false;
			checkFormValidity();
			return;
		}
		clearTimeout(timer);
		if (field === 'email') {
			if (!isValidEmail(value)) {
				return;
			};
		}

		timer = setTimeout(() => {
			fetch('check_username.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded'
				},
				body: `field=${encodeURIComponent(field)}&value=${encodeURIComponent(value)}`
			})
				.then(res => res.json())
				.then(data => setBorder(id, data.available))
				.catch(error => {
					console.log(error);
					usernameValid = false;
					checkFormValidity();
				});
		}, 500);
		input.addEventListener('blur', () => {
			const value = input.value.trim();

			if (value.length < 1) {
				input.style.border = '';
				statusDisplay.style.display = 'none';
				return;
			}
		});
	});
}

function isValidEmail(value) {
	const emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;

	if (emailRegex.test(value)) {
		return true;
	} else {
		return false;
	}
}


function setBorder(id, available) {
	const statusDisplay = document.getElementById(`${id}-status`);
	const input = document.getElementById(id);
	const field = id;
	if (available) {
		input.style.border = '5px solid #19e34f';
		statusDisplay.style.display = 'none';
		if (field === 'username') {
			usernameValid = true;
		} else {
			emailValid = true;
		}
	} else {
		input.style.border = '5px solid red';
		statusDisplay.innerHTML = `${field} already taken`;
		statusDisplay.style.display = 'block';
		statusDisplay.style.fontWeight = 'bold';
		statusDisplay.style.color = 'red';
		if (field === 'username') {
			usernameValid = false;
		} else {
			emailValid = false;
		}
	}
	checkFormValidity();
}


