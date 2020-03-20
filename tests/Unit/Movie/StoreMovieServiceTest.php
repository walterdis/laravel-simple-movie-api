<?php

namespace Tests\Unit\Movie;

use App\Http\Middleware\Auth\ProtectWithJwt;
use App\Http\Services\Movie\StoreMovieService;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreMovieServiceTest extends TestCase
{

    use RefreshDatabase, WithFaker;
    use InteractsWithDatabase;

    /**
     * @var User
     */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $this->withoutMiddleware(ProtectWithJwt::class);
    }

    /**
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     *
     * @param string $route
     * @param array $data
     *
     * @return TestResponse
     */
    protected function getStoreResponse(string $route, array $data): TestResponse
    {
        return $this->actingAs($this->user, 'api')
            ->postJson($route, $data);
    }

    /**
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     *
     * @param $name
     * @param $size
     * @param $mime
     *
     * @return File
     */
    protected function getFakeFile($name, $size, $mime = 'text/plain'): File
    {
        Storage::fake('public');

        return UploadedFile::fake()
            ->create($name)
            ->size($size)
            ->mimeType($mime);
    }

    /**
     * @test
     *
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     */
    public function request_fail_when_name_is_missing()
    {
        $response = $this->getStoreResponse(route('movies.store'), []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrors('name');
    }

    /**
     * @test
     *
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     */
    public function request_fail_when_name_exceed_max_characters()
    {
        $response = $this->getStoreResponse(route('movies.store'), [
            'name' => str_repeat('a', 256),
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrors('name');
    }

    /**
     * @test
     *
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     */
    public function request_fail_when_uploaded_file_is_missing()
    {
        $response = $this->getStoreResponse(route('movies.store'), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrors('uploaded_file');
    }

    /**
     * @test
     *
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     */
    public function request_fail_when_uploaded_file_is_exceed_size()
    {
        $fileSize = 1024 * 6; // Exceder o valor permitido de 5MB

        $file = $this->getFakeFile('movie1.jpg', $fileSize, 'video/x-msvideo');

        $response = $this->getStoreResponse(route('movies.store'), [
            'name' => 'filme 1',
            'uploaded_file' => $file,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrors('uploaded_file');
    }

    /**
     * @test
     *
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     */
    public function request_fail_when_uploaded_file_mime_is_invalid()
    {
        $fileSize = 1024 * 1;

        $file = $this->getFakeFile('movie1.jpg', $fileSize, 'text-plain');

        $response = $this->getStoreResponse(route('movies.store'), [
            'name' => 'filme 1',
            'uploaded_file' => $file,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrors('uploaded_file');
    }

    /**
     * @test
     *
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     */
    public function succes_when_uploaded_file_exists()
    {
        Storage::disk('public')->deleteDir('movies');

        $fileSize = 1024 * 1;
        $file = $this->getFakeFile('movie1.jpg', $fileSize, 'video/x-msvideo');

        $response = $this->getStoreResponse(route('movies.store'), [
            'name' => 'filme 1',
            'uploaded_file' => $file,
        ]);

        /** @var Movie $movie */
        $movie = $response->getOriginalContent()['movie'];

        Storage::disk('public')->assertExists($movie->getRelativeStoredFile());
    }
}
