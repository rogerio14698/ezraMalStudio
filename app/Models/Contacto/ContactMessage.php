<?php

namespace App\Models\Contacto;

use App\Enums\EstadoContacto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Mensaje recibido desde el formulario público de contacto.
 */
class ContactMessage extends Model
{
    protected $table = 'contact_messages';

    /**
     * 'status', 'read_at' y 'replied_at' se cambian solo con los métodos
     * de abajo, no desde el formulario público.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'ip_address',
        'user_agent',
    ];

    protected $attributes = [
        'status' => 'no_leido',
    ];

    protected function casts(): array
    {
        return [
            'status' => EstadoContacto::class,
            'read_at' => 'datetime',
            'replied_at' => 'datetime',
        ];
    }

    public function scopeNoLeidos(Builder $query): Builder
    {
        return $query->where('status', EstadoContacto::NoLeido);
    }

    public function markAsRead(): void
    {
        if ($this->status === EstadoContacto::NoLeido) {
            $this->forceFill(['status' => EstadoContacto::Leido, 'read_at' => now()])->save();
        }
    }

    public function markAsReplied(): void
    {
        $this->forceFill([
            'status' => EstadoContacto::Respondido,
            'read_at' => $this->read_at ?? now(),
            'replied_at' => now(),
        ])->save();
    }
}
