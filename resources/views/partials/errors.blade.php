<div v-if="errors.length" class="alert alert-danger">
    <ul class="mb-0">
        <li v-for="error in errors">@{{ error }}</li>
    </ul>
</div>