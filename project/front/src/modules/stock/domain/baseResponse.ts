export interface ResponseData<T = []> {
  data: T;
  status: number;
  message: string;
}

export interface ResponseBasic<T = []> {
  data: T;
}
