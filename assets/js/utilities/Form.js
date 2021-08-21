import Errors from './Errors';

class Form {
    /**
     * Create a new Form instance.
     *
     * @param {object} data
     */
    constructor(data) {
        this.originalData = data;

        for (let field in data) {
            this[field] = data[field];
        }

        this.errors = new Errors();
    }

    /**
     * Fetch all relevant data for the form.
     */
    data() {
        let data = {};

        for (let property in this.originalData) {
            data[property] = this[property];
        }

        return data;
    }

    /**
     * Reset the form fields.
     */
    reset(ignore = []) {
        for (let field in this.originalData) {
            if($.inArray(field, ignore) === -1) {
                var fieldType = $("#input-" + field).attr("type");
                var multiple = $("#input-" + field).attr("multiple");
                if (fieldType === "checkbox") {
                    this[field] = false;
                } else if (multiple) {
                    this[field] = [];
                } /*else if(typeof this[field] === "number") {
                    this[field] = 0;
                } */else {
                    this[field] = "";
                }
            }
        }

        this.errors.clear();
    }

    /**
     * Send a POST request to the given URL.
     * .
     * @param {string} url
     */
    post(url) {
        return this.submit('post', url);
    }

    /**
     * Send a PUT request to the given URL.
     * .
     * @param {string} url
     */
    put(url) {
        return this.submit('put', url);
    }

    /**
     * Send a PATCH request to the given URL.
     * .
     * @param {string} url
     */
    patch(url) {
        return this.submit('patch', url);
    }

    /**
     * Send a DELETE request to the given URL.
     * .
     * @param {string} url
     */
    delete(url) {
        return this.submit('delete', url);
    }

    /**
     * Submit the form.
     *
     * @param {string} requestType
     * @param {string} url
     */
    submit(requestType, url, reset = true) {
        return new Promise((resolve, reject) => {
            axios[requestType](url, this.data())
                    .then(response => {
                        if (reset) {
                            this.onSuccess(response.data);
                        } else {
                            this.errors.clear();
                        }

                        resolve(response.data);
                    })
                    .catch(error => {
                        this.onFail(error.response.data);

                        reject(error.response.data);
                    });
        });
    }

    /**
     * Handle a successful form submission.
     *
     * @param {object} data
     */
    onSuccess(data) {
        this.reset();
    }

    /**
     * Handle a failed form submission.
     *
     * @param {object} errors
     */
    onFail(errors) {
        this.errors.record(errors.errors);
    }
}

export default Form;