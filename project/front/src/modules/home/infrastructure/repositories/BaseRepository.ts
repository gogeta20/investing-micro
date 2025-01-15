abstract class BaseRepository  {

  path: string = '';

  setEndPoint(path: string): void {
    this.path = path;
  }
}

export default BaseRepository;
