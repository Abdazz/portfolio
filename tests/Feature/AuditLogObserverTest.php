<?php

use App\Models\AuditLog;
use App\Models\Award;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('creating a domain model writes a created audit-log entry', function () {
    $admin = User::factory()->create();
    $this->actingAs($admin);

    $experience = Experience::factory()->create([
        'company' => 'Acme Corp',
    ]);

    $log = AuditLog::where('action', 'created')
        ->where('subject_type', Experience::class)
        ->where('subject_id', $experience->id)
        ->first();

    expect($log)->not->toBeNull()
        ->and($log->user_id)->toBe($admin->id)
        ->and($log->payload['new'])->toHaveKey('company', 'Acme Corp');
});

test('updating a domain model writes an updated audit-log entry with a diff', function () {
    $admin = User::factory()->create();
    $this->actingAs($admin);

    $skill = Skill::factory()->create(['category' => 'Backend']);

    AuditLog::query()->delete(); // clear create entry to isolate

    $skill->update(['category' => 'Frontend']);

    $log = AuditLog::where('action', 'updated')
        ->where('subject_type', Skill::class)
        ->where('subject_id', $skill->id)
        ->first();

    expect($log)->not->toBeNull()
        ->and($log->payload['old'])->toHaveKey('category', 'Backend')
        ->and($log->payload['new'])->toHaveKey('category', 'Frontend');
});

test('deleting a domain model writes a deleted audit-log entry', function () {
    $admin = User::factory()->create();
    $this->actingAs($admin);

    $experience = Experience::factory()->create();

    AuditLog::query()->delete(); // clear create entry to isolate

    $experience->delete();

    $log = AuditLog::where('action', 'deleted')
        ->where('subject_type', Experience::class)
        ->where('subject_id', $experience->id)
        ->first();

    expect($log)->not->toBeNull()
        ->and($log->payload['old'])->not->toBeEmpty();
});

test('audit log records the acting user id', function () {
    $admin = User::factory()->create();
    $this->actingAs($admin);

    $skill = Skill::factory()->create();

    $log = AuditLog::where('action', 'created')
        ->where('subject_type', Skill::class)
        ->first();

    expect($log->user_id)->toBe($admin->id);
});

test('audit log records null user_id for unauthenticated writes (e.g. seeders)', function () {
    $skill = Skill::factory()->create();

    $log = AuditLog::where('action', 'created')
        ->where('subject_type', Skill::class)
        ->first();

    expect($log->user_id)->toBeNull();
});

test('audit log timestamps are excluded from the diff payload', function () {
    $admin = User::factory()->create();
    $this->actingAs($admin);

    $skill = Skill::factory()->create();

    $log = AuditLog::where('action', 'created')->first();

    expect($log->payload['new'])->not->toHaveKey('created_at')
        ->and($log->payload['new'])->not->toHaveKey('updated_at');
});

test('creating an award writes a created audit-log entry', function () {
    $admin = User::factory()->create();
    $this->actingAs($admin);

    $award = Award::factory()->create([
        'issuer' => 'Acme Foundation',
    ]);

    $log = AuditLog::where('action', 'created')
        ->where('subject_type', Award::class)
        ->where('subject_id', $award->id)
        ->first();

    expect($log)->not->toBeNull()
        ->and($log->user_id)->toBe($admin->id)
        ->and($log->payload['new'])->toHaveKey('issuer', 'Acme Foundation');
});

test('a no-op update (only updated_at changes) does not write an audit-log entry', function () {
    $admin = User::factory()->create();
    $this->actingAs($admin);

    $skill = Skill::factory()->create();

    AuditLog::query()->delete(); // clear create entry to isolate

    $skill->touch(); // only mutates updated_at

    expect(AuditLog::count())->toBe(0);
});
