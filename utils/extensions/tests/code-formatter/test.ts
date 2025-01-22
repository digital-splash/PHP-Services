class TestTs {
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

const first_name_test_ts = 'Jon';
const last_name_test_ts = 'Doe';
const test_ts = new TestTs(first_name_test_ts, last_name_test_ts);
const full_name_test_ts = test_ts.GenerateName();
console.log(full_name_test_ts);
