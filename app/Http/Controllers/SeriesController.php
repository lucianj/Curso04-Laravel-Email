<?php

namespace App\Http\Controllers;

use App\Events\SeriesDeleted;
use App\Http\Requests\SeriesFormRequest;
use App\Mail\SeriesCreated;
use App\Models\Series;
use App\Models\User;
use App\Repositories\SeriesRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SeriesController extends Controller
{
    public function __construct(private SeriesRepository $repository)
    {
        $this->middleware('auth')->except('index');
    }

    public function index(Request $request)
    {
        $series = Series::all();
        $mensagemSucesso = session('mensagem.sucesso');

        return view('series.index')->with('series', $series)
            ->with('mensagemSucesso', $mensagemSucesso);
    }

    public function create()
    {
        return view('series.create');
    }

    public function store(SeriesFormRequest $request)
    {
        //dd($request->file('cover'));

        if ($request->file('cover') == null){
            $coverPath = 'default/default.jpg.jpg';
        }else{
            $coverPath = $request->file('cover')->store('series_cover', 'public');
        }
        
        //if (empty($coverPath)){
        //    $coverPath = asset('/storage/app/public/series_cover/default.jpg');
        //}

        $request->coverPath = $coverPath;
        $serie = $this->repository->add($request);
        
        \App\Events\SeriesCreated::dispatch(
            $serie->nome,
            $serie->id,
            $request->seasonsQty,
            $request->episodesPerSeason,
        );

        return to_route('series.index')
            ->with('mensagem.sucesso', "Série '{$serie->nome}' adicionada com sucesso");

        //$seriesCreatedEvent = new \App\Events\SeriesCreated(
        //    $serie->nome,
        //    $serie->id,
        //    $request->seasonQty,
        //    $request->episodesPerSeason,
        //)
        //\App\Events\SeriesCreated::dispatch();



        //$userList = User::all();
       /** foreach ($userList as $index => $user) {
            $email = new SeriesCreated(
                $serie->nome,
                $serie->id,
                $request->seasonsQty,
                $request->episodesPerSeason,
            );
            $when = now()->addSeconds($index * 5);
            //$when = new \DateTime();
            //$when->modify($index*2 . ' 2 seconds');
            Mail::to($user)->later($when, $email);
            //Mail::to($user)->queue($email);
            //Mail::to($user)->send($email);
            //sleep(2);
        }*/ 
    //}


    }

    public function destroy(Series $series)
    {
        //$SerieData = $series->replicate();
        $series->delete();
        event(new SeriesDeleted($series));

        return to_route('series.index')
            ->with('mensagem.sucesso', "Série '{$series->nome}' removida com sucesso");
    }

    public function edit(Series $series)
    {
        return view('series.edit')->with('serie', $series);
    }

    public function update(Series $series, SeriesFormRequest $request)
    {
        $series->fill($request->all());
        $series->save();

        return to_route('series.index')
            ->with('mensagem.sucesso', "Série '{$series->nome}' atualizada com sucesso");
    }
}
