class TestJs {
	first_name = '';
	last_name = '';

	constructor(first_name, last_name) {
		this.first_name = first_name;
		this.last_name = last_name;
	}

	GenerateName() {
		return `${this.first_name} ${this.last_name}`;
	}
}

const first_name_test_js = 'Jon';
const last_name_test_js = 'Doe';
const test_js = new TestJs(first_name_test_js, last_name_test_js);
const full_name_test_js = test_js.GenerateName();
console.log(full_name_test_js);
