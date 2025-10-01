export default function countrySelect(allCountries, initialSelected) {
    return {
        allCountries,
        selectedCountries: [],
        newCountry: '',
        init() {
            // pre-fill old input if validation failed
            this.selectedCountries = this.allCountries.filter(c => initialSelected.includes(c.id));
        },
        addCountry(event) {
            const id = parseInt(this.newCountry);
            if (!id) return;
            const country = this.allCountries.find(c => c.id === id);
            if (country && !this.selectedCountries.find(c => c.id === id)) {
                this.selectedCountries.push(country);
            }
            this.newCountry = '';
            event.target.value = '';
        },
        removeCountry(id) {
            this.selectedCountries = this.selectedCountries.filter(c => c.id !== id);
        }
    }
}
