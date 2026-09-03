<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class CreateAdmin extends Command
{
    protected $signature = 'app:create-admin';

    protected $description = 'Cria uma conta administrativa ou promove uma conta existente';

    public function handle(): int
    {
        $credentials = [
            'name' => preg_replace('/\s+/', ' ', trim((string) $this->ask('Nome'))),
            'email' => Str::lower(trim((string) $this->ask('E-mail'))),
            'password' => (string) $this->secret('Senha'),
            'password_confirmation' => (string) $this->secret('Confirme a senha'),
        ];

        $validator = Validator::make($credentials, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'name.required' => 'Informe o nome.',
            'name.max' => 'O nome deve ter no máximo 100 caracteres.',
            'email.required' => 'Informe o e-mail.',
            'email.email' => 'Informe um e-mail válido.',
            'password.required' => 'Informe a senha.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = User::query()->where('email', $credentials['email'])->first();
        $wasCreated = $user === null;

        $user ??= new User;
        $user->forceFill([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'is_admin' => true,
        ])->save();

        $this->info($wasCreated
            ? 'Conta administrativa criada com sucesso.'
            : 'Conta existente promovida a administradora com sucesso.');

        return self::SUCCESS;
    }
}
