<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('reports', static function (Blueprint $table): void {
            $table->ulid('id')->primary();

            $table->string('url');
            $table->string('content_type');

            $table->unsignedInteger('status')->default(Response::HTTP_OK);
            $table->unsignedInteger('header_size')->default(0);
            $table->unsignedInteger('request_size')->default(0);
            $table->unsignedInteger('redirect_count')->default(0);
            $table->unsignedInteger('http_version')->default(0);

            $table->unsignedInteger('appconnect_time')->default(0);
            $table->unsignedInteger('connect_time')->default(0);
            $table->unsignedInteger('namelookup_time')->default(0);
            $table->unsignedInteger('pretransfer_time')->default(0);
            $table->unsignedInteger('redirect_time')->default(0);
            $table->unsignedInteger('starttransfer_time')->default(0);
            $table->unsignedInteger('total_time')->default(0);

            $table
                ->foreignUlid('check_id')
                ->index()
                ->constrained('checks')
                ->cascadeOnDelete();
            $table->timestamp('started_at');
            $table->timestamp('finished_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
