<section>
    <header class="mb-4">
        <h4 class="text-lg font-medium text-danger fw-bold">
            {{ __('Hapus Akun') }}
        </h4>

        <p class="mt-1 text-sm text-muted">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.') }}
        </p>
    </header>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
        {{ __('Hapus Akun') }}
    </button>

    <!-- Modal -->
    <div class="modal fade {{ $errors->userDeletion->isNotEmpty() ? 'show' : '' }}" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="{{ $errors->userDeletion->isNotEmpty() ? 'false' : 'true' }}" style="{{ $errors->userDeletion->isNotEmpty() ? 'display: block;' : '' }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="confirmUserDeletionModalLabel">{{ __('Apakah Anda yakin ingin menghapus akun Anda?') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <p class="text-muted text-sm">
                            {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Silakan masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}
                        </p>

                        <div class="mt-3">
                            <label for="password" class="form-label sr-only">{{ __('Password') }}</label>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                placeholder="{{ __('Password') }}"
                            />

                            @error('password', 'userDeletion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Batal') }}
                        </button>
                        <button type="submit" class="btn btn-danger">
                            {{ __('Hapus Akun') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @if($errors->userDeletion->isNotEmpty())
    <div class="modal-backdrop fade show"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var myModal = new bootstrap.Modal(document.getElementById('confirmUserDeletionModal'), {
                keyboard: false
            });
            myModal.show();
        });
    </script>
    @endif
</section>
